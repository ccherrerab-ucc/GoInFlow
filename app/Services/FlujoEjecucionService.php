<?php

namespace App\Services;

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
 * Principio: Responsabilidad Única — orquesta exclusivamente el flujo de aprobación.
 * Usa modelos directamente (no repositorios) porque la lógica es transaccional
 * y requiere coordinar múltiples tablas en una sola unidad de trabajo.
 */
class FlujoEjecucionService
{
    /**
     * Envía una evidencia a revisión iniciando el flujo configurado para su aspecto.
     * Solo aplica si la evidencia está en Borrador y no tiene un flujo activo.
     *
     * @throws RuntimeException si el aspecto no tiene flujo activo o el flujo no tiene pasos.
     */
    public function iniciarFlujo(Evidencia $evidencia): void
    {
        if ($evidencia->estado_actual !== 1) {
            throw new RuntimeException('Solo se puede enviar a revisión una evidencia en estado Borrador.');
        }

        $tieneFlujoActivo = FlujoEjecucion::where('id_evidencia', $evidencia->id_evidencia)
            ->whereNull('finalizado_at')
            ->exists();

        if ($tieneFlujoActivo) {
            throw new RuntimeException('Esta evidencia ya tiene un flujo de aprobación en curso.');
        }

        $flujo      = $this->resolverFlujoParaEvidencia($evidencia);
        $primerPaso = $flujo->pasos()->orderBy('orden')->first();

        if (!$primerPaso) {
            throw new RuntimeException('El flujo configurado no tiene pasos. Contacta al responsable del aspecto.');
        }

        DB::transaction(function () use ($evidencia, $flujo, $primerPaso) {
            $ejecucion = FlujoEjecucion::create([
                'id_evidencia'  => $evidencia->id_evidencia,
                'id_flujo'      => $flujo->id_flujo,
                'paso_actual'   => $primerPaso->id_paso,
                'estado_actual' => 2, // En revisión
                'iniciado_at'   => now(),
            ]);

            $evidencia->update(['estado_actual' => 2]);

            $this->registrarHistorial($ejecucion, $primerPaso, 'iniciado', null);
        });
    }

    /**
     * Procesa la decisión (aprobado / rechazado) del aprobador actual.
     * Valida que el usuario tiene el rol requerido para el paso vigente.
     *
     * @throws AuthorizationException si el usuario no tiene el rol correcto.
     * @throws RuntimeException       si no hay flujo activo para la evidencia.
     */
    public function procesarDecision(int $evidenciaId, string $decision, ?string $comentario): void
    {
        $ejecucion = FlujoEjecucion::where('id_evidencia', $evidenciaId)
            ->whereNull('finalizado_at')
            ->with(['pasoActual', 'evidencia'])
            ->firstOrFail();

        $this->verificarRolAprobador($ejecucion->pasoActual);

        DB::transaction(function () use ($ejecucion, $decision, $comentario) {
            $evidencia  = $ejecucion->evidencia;
            $pasoActual = $ejecucion->pasoActual;

            $this->registrarHistorial($ejecucion, $pasoActual, $decision, $comentario);

            if ($decision === 'rechazado') {
                $evidencia->update(['estado_actual' => 4]);
                $ejecucion->update([
                    'estado_actual' => 4,
                    'paso_actual'   => null,
                    'finalizado_at' => now(),
                ]);
                return;
            }

            // Aprobado: buscar siguiente paso
            $siguientePaso = FlujoPaso::where('id_flujo', $ejecucion->id_flujo)
                ->where('orden', '>', $pasoActual->orden)
                ->orderBy('orden')
                ->first();

            if ($siguientePaso) {
                $ejecucion->update([
                    'paso_actual'   => $siguientePaso->id_paso,
                    'estado_actual' => 2, // Sigue En revisión
                ]);
                $this->registrarHistorial($ejecucion, $siguientePaso, 'avanzado', null);
            } else {
                // Último paso: evidencia aprobada
                $evidencia->update(['estado_actual' => 3]);
                $ejecucion->update([
                    'estado_actual' => 3,
                    'paso_actual'   => null,
                    'finalizado_at' => now(),
                ]);
            }
        });
    }

    /**
     * Reinicia el flujo desde el primer paso después de una corrección.
     * Solo aplica si la evidencia está en estado Rechazado.
     *
     * @throws RuntimeException si la evidencia no está rechazada.
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

        $primerPaso = $ejecucion->flujo->pasos()->orderBy('orden')->first();

        if (!$primerPaso) {
            throw new RuntimeException('El flujo no tiene pasos configurados.');
        }

        DB::transaction(function () use ($ejecucion, $evidencia, $primerPaso) {
            $ejecucion->update([
                'paso_actual'   => $primerPaso->id_paso,
                'estado_actual' => 2,
                'finalizado_at' => null,
            ]);

            $evidencia->update(['estado_actual' => 2]);

            $this->registrarHistorial($ejecucion, $primerPaso, 'reiniciado', null);
        });
    }

    /* ── Métodos privados de soporte ─────────────────────────── */

    /**
     * Busca el flujo activo configurado para el aspecto de la evidencia.
     *
     * @throws RuntimeException si el aspecto no tiene flujo activo.
     */
    private function resolverFlujoParaEvidencia(Evidencia $evidencia): Flujo
    {
        $flujo = Flujo::where('id_aspecto', $evidencia->id_aspecto)
            ->where('activo', true)
            ->with('pasos')
            ->first();

        if (!$flujo) {
            throw new RuntimeException(
                'El aspecto asociado a esta evidencia no tiene un flujo de aprobación activo. ' .
                'Solicita al responsable del aspecto que configure el flujo.'
            );
        }

        return $flujo;
    }

    /**
     * Valida que el usuario autenticado tiene el rol requerido para aprobar el paso actual.
     *
     * @throws AuthorizationException si el rol no coincide.
     */
    private function verificarRolAprobador(FlujoPaso $paso): void
    {
        if (Auth::user()->id_rol !== $paso->rol_requerido) {
            throw new AuthorizationException(
                'No tienes el rol requerido para tomar decisiones en este paso del flujo.'
            );
        }
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
