<?php

namespace App\Http\Controllers;

use App\Models\Evidencia;
use App\Services\FlujoEjecucionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FlujoEjecucionController extends Controller
{
    public function __construct(private readonly FlujoEjecucionService $service) {}

    /** Envía la evidencia a revisión iniciando el flujo. Solo el creador o roles superiores. */
    public function iniciar(Evidencia $evidencia): RedirectResponse
    {
        $this->authorize('iniciar', $evidencia);

        try {
            $this->service->iniciarFlujo($evidencia);
            return back()->with('success', 'Evidencia enviada a revisión correctamente.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /** Registra la decisión (aprobado / rechazado) — solo aprobadores autorizados. */
    public function decision(Request $request, Evidencia $evidencia): RedirectResponse
    {
        $this->authorize('aprobar', $evidencia);

        $request->validate([
            'decision'   => ['required', 'in:aprobado,rechazado'],
            'comentario' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $this->service->procesarDecision(
                $evidencia->id_evidencia,
                $request->decision,
                $request->comentario
            );

            $mensaje = $request->decision === 'aprobado'
                ? 'Evidencia aprobada correctamente.'
                : 'Evidencia rechazada. El autor podrá corregirla y reenviarla.';

            return back()->with('success', $mensaje);

        } catch (AuthorizationException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /** Reinicia el flujo tras corrección — solo el creador o roles superiores. */
    public function reiniciar(Evidencia $evidencia): RedirectResponse
    {
        $this->authorize('iniciar', $evidencia);

        try {
            $this->service->reiniciarFlujo($evidencia->id_evidencia);
            return back()->with('success', 'Flujo reiniciado. La evidencia está en revisión nuevamente.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
