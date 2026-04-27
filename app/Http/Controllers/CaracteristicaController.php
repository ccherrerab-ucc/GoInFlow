<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaracteristicaRequest;
use App\Models\Caracteristica;
use App\Models\Factor;
use App\Models\StatusCna;
use App\Models\User;
use App\Services\CaracteristicaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CaracteristicaController extends Controller
{
    public function __construct(private readonly CaracteristicaService $service) {}

    public function index(): View
    {
        $this->authorize('viewAny', Caracteristica::class);

        return view('VistaCaracteristicas.index', [
            'caracteristicas' => $this->service->listar(),
        ]);
    }

    public function show(int $id): View
    {
        $caracteristica = $this->service->obtenerParaEvaluacion($id);
        $this->authorize('view', $caracteristica);

        return view('VistaCaracteristicas.Show', compact('caracteristica'));
    }

    public function create(): View
    {
        $this->authorize('create', Caracteristica::class);

        return view('VistaCaracteristicas.create', $this->formData());
    }

    public function store(CaracteristicaRequest $request): RedirectResponse
    {
        $this->authorize('create', Caracteristica::class);

        $this->service->crear($request->validated());

        return redirect()->route('caracteristicas.index')
            ->with('success', 'Característica creada exitosamente.');
    }

    public function edit(int $id): View
    {
        $caracteristica = $this->service->obtener($id);
        $this->authorize('update', $caracteristica);

        return view('VistaCaracteristicas.edit', array_merge(
            $this->formData(),
            ['caracteristica' => $caracteristica]
        ));
    }

    public function update(CaracteristicaRequest $request, int $id): RedirectResponse
    {
        $caracteristica = $this->service->obtener($id);
        $this->authorize('update', $caracteristica);

        $this->service->actualizar($id, $request->validated());

        return redirect()->route('caracteristicas.index')
            ->with('success', 'Característica actualizada exitosamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $caracteristica = $this->service->obtener($id);
        $this->authorize('delete', $caracteristica);

        $this->service->eliminar($id);

        return redirect()->route('caracteristicas.index')
            ->with('success', 'Característica suprimida exitosamente.');
    }

    private function formData(): array
    {
        return [
            'factores'     => Factor::orderBy('name')->get(),
            'statuses'     => StatusCna::all(),
            'responsables' => User::orderBy('name')->get(),
        ];
    }
}
