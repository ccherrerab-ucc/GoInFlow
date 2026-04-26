<?php

namespace App\Observers\Concerns;

use App\Models\Auditoria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Trait que implementa la lógica de auditoría para observadores de Eloquent.
 *
 * Cada observador que use este trait debe declarar:
 *   protected string $auditoriaObjeto = 'NombreDelModelo';
 *
 * Comportamiento por operación:
 *   crear      → 1 registro con valor_nuevo = JSON de todos los atributos
 *   actualizar → 1 registro POR CAMPO modificado (granular)
 *   suprimir   → 1 registro con valor_antiguo = JSON de todos los atributos
 */
trait AuditsChanges
{
    // Campos de meta-gestión que no aportan valor en la auditoría de negocio
    protected array $auditSkipFields = [
        'created_at', 'updated_at', 'created_by', 'updated_by',
    ];

    public function created(Model $model): void
    {
        $snapshot = $this->snapshot($model);
        $this->grabar($model, 'crear', null, null, json_encode($snapshot, JSON_UNESCAPED_UNICODE));
    }

    public function updated(Model $model): void
    {
        // getChanges() = campos que cambiaron con su NUEVO valor (se llena en syncChanges() antes del evento)
        // getOriginal($key) = valor ANTERIOR (syncOriginal() aún no se ejecutó en el evento updated)
        foreach ($model->getChanges() as $campo => $nuevoValor) {
            if (in_array($campo, $this->auditSkipFields)) {
                continue;
            }

            $valorAntiguo = $model->getOriginal($campo);

            $this->grabar(
                $model,
                'actualizar',
                $campo,
                is_null($valorAntiguo) ? null : (string) $valorAntiguo,
                is_null($nuevoValor)   ? null : (string) $nuevoValor,
            );
        }
    }

    public function deleted(Model $model): void
    {
        $snapshot = $this->snapshot($model);
        $this->grabar($model, 'suprimir', null, json_encode($snapshot, JSON_UNESCAPED_UNICODE), null);
    }

    // ──────────────────────────────────────────────
    // Helpers privados
    // ──────────────────────────────────────────────

    private function snapshot(Model $model): array
    {
        return collect($model->getAttributes())
            ->except($this->auditSkipFields)
            ->toArray();
    }

    private function grabar(Model $model, string $operacion, ?string $atributo, ?string $valorAntiguo, ?string $valorNuevo): void
    {
        try {
            $userId = Auth::id();

            Auditoria::create([
                'objeto'             => $this->auditoriaObjeto,
                'registro'           => (string) $model->getKey(),
                'atributo'           => $atributo,
                'operacion'          => $operacion,
                'valor_antiguo'      => $valorAntiguo,
                'valor_nuevo'        => $valorNuevo,
                'modificado_por'     => $userId,
                'fecha_modificacion' => now(),
                'created_by'         => $userId,
                'updated_by'         => $userId,
                'status_id'          => 1, // 'active' en tabla status
            ]);
        } catch (\Throwable $e) {
            // La auditoría nunca debe interrumpir la operación principal
            Log::warning("Auditoría fallida [{$this->auditoriaObjeto}#{$model->getKey()}] {$operacion}: {$e->getMessage()}");
        }
    }
}
