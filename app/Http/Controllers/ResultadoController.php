<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultadoRequest;
use App\Models\Factor;
use App\Models\Resultado;
use App\Models\StatusCna;
use App\Services\ResultadoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Principio: Responsabilidad Única — solo orquesta, no valida ni persiste.
 * Principio: Inversión de Dependencias — depende del servicio, no del modelo.
 */
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

        $this->service->crear($request->validated());

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

        $this->service->actualizar($id, $request->validated());

        return redirect()->route('resultados.index')
            ->with('success', 'Resultado actualizado exitosamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $resultado = $this->service->obtener($id);
        $this->authorize('delete', $resultado);

        $this->service->eliminar($id);

        return redirect()->route('resultados.index')
            ->with('success', 'Resultado eliminado exitosamente.');
    }

    /* ── Datos compartidos para create/edit ── */
    private function formData(): array
    {
        $factores = Factor::with([
            'caracteristicas' => fn($q) => $q->orderBy('name')
                ->with(['aspectos' => fn($q2) => $q2->orderBy('name')
                    ->with(['evidencias' => fn($q3) => $q3->orderBy('nombre')])]),
        ])->orderBy('name')->get();

        return [
            'statuses' => StatusCna::all(),
            'factores' => $factores,
        ];
    }
}
