<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultadoRequest;
use App\Models\Evidencia;
use App\Models\Factor;
use App\Models\Resultado;
use App\Models\StatusCna;
use App\Models\User;
use App\Services\ResultadoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResultadoController extends Controller
{
    public function __construct(private readonly ResultadoService $service) {}

    public function index(): View
    {
        $this->authorize('viewAny', Resultado::class);

        return view('VistaResultados.Index', [
            'resultados' => $this->service->listar(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Resultado::class);

        return view('VistaResultados.Create', $this->formData());
    }

    public function store(ResultadoRequest $request): RedirectResponse
    {
        $this->authorize('create', Resultado::class);

        $datos = $request->validated();
        $this->validarAccesoEvidencias($datos['evidencias'] ?? [], $this->usuarioActual());

        $this->service->crear($datos);

        return redirect()->route('resultados.index')
            ->with('success', 'Resultado creado exitosamente.');
    }

    public function edit(int $id): View
    {
        $resultado = $this->service->obtener($id);
        $this->authorize('update', $resultado);

        return view('VistaResultados.Edit', array_merge(
            $this->formData(),
            ['resultado' => $resultado]
        ));
    }

    public function update(ResultadoRequest $request, int $id): RedirectResponse
    {
        $resultado = $this->service->obtener($id);
        $this->authorize('update', $resultado);

        $datos = $request->validated();
        $this->validarAccesoEvidencias($datos['evidencias'] ?? [], $this->usuarioActual());

        $this->service->actualizar($id, $datos);

        return redirect()->route('resultados.index')
            ->with('success', 'Resultado actualizado exitosamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $resultado = $this->service->obtener($id);
        $this->authorize('delete', $resultado);

        $this->service->eliminar($id);

        return redirect()->route('resultados.index')
            ->with('success', 'Resultado suprimido exitosamente.');
    }

    /* ── Datos compartidos para create/edit ── */
    private function formData(): array
    {
        $user = $this->usuarioActual();

        $factores = Factor::with([
            'caracteristicas' => function ($q) use ($user) {
                // Líder: solo sus propias características
                if ($user?->isLiderCaracteristica()) {
                    $q->where('responsable', $user->id);
                }
                $q->orderBy('name')->with([
                    'aspectos' => function ($q2) use ($user) {
                        // Enlace: solo los aspectos donde es responsable
                        if ($user?->isEnlace()) {
                            $q2->where('responsable', $user->id);
                        }
                        $q2->orderBy('name')->with([
                            'evidencias' => fn($q3) => $q3
                                ->where('estado_actual', 3) // solo aprobadas
                                ->orderBy('nombre'),
                        ]);
                    },
                ]);
            },
        ])->orderBy('name')->get();

        return [
            'statuses' => StatusCna::all(),
            'factores' => $factores,
        ];
    }

    /* ── Helpers ── */

    private function usuarioActual(): ?User
    {
        $raw = Auth::user();
        return $raw instanceof User ? $raw : null;
    }

    /**
     * Verifica que todas las evidencias enviadas sean aprobadas
     * y pertenezcan al ámbito accesible del usuario.
     */
    private function validarAccesoEvidencias(array $evidenciaIds, ?User $user): void
    {
        if (empty($evidenciaIds) || $user === null) return;

        // Admin y DirPrograma pueden usar cualquier evidencia aprobada
        if ($user->isAdmin() || $user->isDirPrograma()) return;

        $q = Evidencia::whereIn('id_evidencia', $evidenciaIds)
            ->where('estado_actual', 3);

        if ($user->isEnlace()) {
            // Enlace: solo evidencias de aspectos donde es responsable
            $q->whereHas('aspecto', fn($aq) => $aq->where('responsable', $user->id));
        } elseif ($user->isLiderCaracteristica()) {
            // Líder: evidencias de aspectos en sus características
            $q->whereHas('aspecto.caracteristica', fn($aq) => $aq->where('responsable', $user->id));
        }

        if ($q->count() !== count($evidenciaIds)) {
            abort(403);
        }
    }
}
