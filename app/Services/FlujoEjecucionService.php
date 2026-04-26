<?php

namespace App\Services;

use App\Models\Caracteristica;
use App\Models\Evidencia;
use App\Models\Flujo;
use App\Models\FlujoEjecucion;
use App\Models\FlujoHistorial;
use App\Models\FlujoPaso;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Orquesta el flujo de aprobación de evidencias.
 * Aprobador = siempre el responsable de la característica.
 * El flujo se auto-crea al primer envío si no existe.
 */
class FlujoEjecucionService
{
    /**
     * Envía una evidencia a revisión.
     * Requiere que esté en Borrador y sin flujo activo.
     * El flujo se auto-genera ligado al responsable de la característica.
     */
    public function iniciarFlujo(Evidencia $evidencia): void
    {
        if ($evidencia->estado_actual !== 1) {
            throw new RuntimeException('Solo se puede enviar a revisión una evidencia en estado Borrador.');
        }

        if (FlujoEjecucion::where('id_evidencia', $evidencia->id_evidencia)->whereNull('finalizado_at')->exists()) {
            throw new RuntimeException('Esta evidencia ya tiene un flujo de aprobación en curso.');
        }

        $aspecto        = $this->cargarAspecto($evidencia);
        $caracteristica = $this->cargarCaracteristica($aspecto);
        $flujo          = $this->obtenerOCrearFlujo($caracteristica);
        $primerPaso     = $flujo->pasos()->first();

        DB::transaction(function () use ($evidencia, $flujo, $primerPaso) {
            $ejecucion = FlujoEjecucion::create([
                'id_evidencia'  => $evidencia->id_evidencia,
                'id_flujo'      => $flujo->id_flujo,
                'paso_actual'   => $primerPaso->id_paso,
                'estado_actual' => 2,
                'iniciado_at'   => now(),
            ]);

            $evidencia->update(['estado_actual' => 2]);
            $this->registrarHistorial($ejecucion, $primerPaso, 'iniciado', null);
        });
    }

    /**
     * Procesa la decisión del responsable de la característica (aprobado/rechazado).
     */
    public function procesarDecision(int $evidenciaId, string $decision, ?string $comentario): void
    {
        $ejecucion = FlujoEjecucion::where('id_evidencia', $evidenciaId)
            ->whereNull('finalizado_at')
            ->with(['pasoActual', 'evidencia.aspecto.caracteristica'])
            ->firstOrFail();

        $this->verificarEsResponsable($ejecucion->evidencia);

        DB::transaction(function () use ($ejecucion, $decision, $comentario) {
            $evidencia  = $ejecucion->evidencia;
            $pasoActual = $ejecucion->pasoActual;

            $this->registrarHistorial($ejecucion, $pasoActual, $decision, $comentario);

            if ($decision === 'rechazado') {
                $evidencia->update(['estado_actual' => 4]);
                $ejecucion->update(['estado_actual' => 4, 'paso_actual' => null, 'finalizado_at' => now()]);
                return;
            }

            // Aprobado: avanzar al siguiente paso si existe
            $siguientePaso = FlujoPaso::where('id_flujo', $ejecucion->id_flujo)
                ->where('orden', '>', $pasoActual->orden)
                ->orderBy('orden')
                ->first();

            if ($siguientePaso) {
                $ejecucion->update(['paso_actual' => $siguientePaso->id_paso, 'estado_actual' => 2]);
                $this->registrarHistorial($ejecucion, $siguientePaso, 'avanzado', null);
            } else {
                $evidencia->update(['estado_actual' => 3]);
                $ejecucion->update(['estado_actual' => 3, 'paso_actual' => null, 'finalizado_at' => now()]);
            }
        });
    }

    /**
     * Reinicia el flujo desde el primer paso tras corrección del creador.
     */
    public function reiniciarFlujo(int $evidenciaId): void
    {
        $ejecucion = FlujoEjecucion::where('id_evidencia', $evidenciaId)
            ->orderByDesc('id_ejecucion')
            ->with(['flujo.pasos', 'evidencia'])
            ->firstOrFail();

        $evidencia = $ejecucion->evidencia;

        if ($evidencia->estado_actual !== 4) {
            throw new RuntimeException('Solo se puede reiniciar el flujo de una evidencia rechazada.');
        }

        $primerPaso = $ejecucion->flujo->pasos()->first();

        if (!$primerPaso) {
            throw new RuntimeException('El flujo no tiene pasos configurados.');
        }

        DB::transaction(function () use ($ejecucion, $evidencia, $primerPaso) {
            $ejecucion->update(['paso_actual' => $primerPaso->id_paso, 'estado_actual' => 2, 'finalizado_at' => null]);
            $evidencia->update(['estado_actual' => 2]);
            $this->registrarHistorial($ejecucion, $primerPaso, 'reiniciado', null);
        });
    }

    /* ── Privados ──────────────────────────────────────────────── */

    /**
     * Obtiene el flujo de la característica; si no existe lo crea con un único paso
     * cuyo aprobador es el responsable de la característica.
     */
    private function obtenerOCrearFlujo(Caracteristica $caracteristica): Flujo
    {
        $flujo = Flujo::where('id_caracteristica', $caracteristica->id_caracteristica)
            ->whereNull('id_aspecto')
            ->where('activo', true)
            ->with('pasos')
            ->first();

        if ($flujo && $flujo->pasos->isNotEmpty()) {
            return $flujo;
        }

        $responsable = $caracteristica->responsableUser
            ?? $caracteristica->responsableUser()->first();

        if (!$responsable) {
            throw new RuntimeException('La característica no tiene un responsable asignado. Contacta al administrador.');
        }

        if (!$responsable->id_rol) {
            throw new RuntimeException('El responsable de la característica no tiene un rol asignado.');
        }

        return DB::transaction(function () use ($caracteristica, $responsable, $flujo) {
            if (!$flujo) {
                $flujo = Flujo::create([
                    'nombre'            => 'Aprobación — ' . $caracteristica->name,
                    'id_caracteristica' => $caracteristica->id_caracteristica,
                    'id_aspecto'        => null,
                    'activo'            => true,
                ]);
            }

            FlujoPaso::create([
                'id_flujo'             => $flujo->id_flujo,
                'orden'                => 1,
                'rol_requerido'        => $responsable->id_rol,
                'cantidad_aprobadores' => 1,
            ]);

            return $flujo->load('pasos');
        });
    }

    /**
     * Valida que el usuario autenticado es el responsable de la característica
     * a la que pertenece la evidencia.
     *
     * @throws AuthorizationException
     */
    private function verificarEsResponsable(Evidencia $evidencia): void
    {
        $caracteristica = $evidencia->aspecto?->caracteristica;

        if (!$caracteristica || Auth::id() !== (int) $caracteristica->responsable) {
            throw new AuthorizationException(
                'Solo el responsable de la característica puede aprobar o rechazar esta evidencia.'
            );
        }
    }

    private function cargarAspecto(Evidencia $evidencia)
    {
        return $evidencia->relationLoaded('aspecto')
            ? $evidencia->aspecto
            : $evidencia->aspecto()->firstOrFail();
    }

    private function cargarCaracteristica($aspecto): Caracteristica
    {
        $caracteristica = $aspecto->relationLoaded('caracteristica')
            ? $aspecto->caracteristica
            : $aspecto->caracteristica()->firstOrFail();

        if (!$caracteristica) {
            throw new RuntimeException('El aspecto no está asociado a una característica.');
        }

        return $caracteristica;
    }

    private function registrarHistorial(
        FlujoEjecucion $ejecucion,
        ?FlujoPaso     $paso,
        string         $decision,
        ?string        $comentario
    ): void {
        FlujoHistorial::create([
            'id_ejecucion' => $ejecucion->id_ejecucion,
            'id_paso'      => $paso?->id_paso,
            'usuario_id'   => Auth::id(),
            'decision'     => $decision,
            'comentario'   => $comentario,
            'fecha'        => now(),
        ]);
    }
}
