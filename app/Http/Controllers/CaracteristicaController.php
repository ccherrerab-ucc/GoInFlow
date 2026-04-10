<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaracteristicaRequest;
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
        return view('VistaCaracteristicas.index', [
            'caracteristicas' => $this->service->listar(),
        ]);
    }

    public function create(): View
    {
        return view('VistaCaracteristicas.create', $this->formData());
    }

    public function store(CaracteristicaRequest $request): RedirectResponse
    {
        $this->service->crear($request->validated());

        return redirect()->route('caracteristicas.index')
            ->with('success', 'Característica creada exitosamente.');
    }

    public function edit(int $id): View
    {
        return view('VistaCaracteristicas.edit', array_merge(
            $this->formData(),
            ['caracteristica' => $this->service->obtener($id)]
        ));
    }

    public function update(CaracteristicaRequest $request, int $id): RedirectResponse
    {
        $this->service->actualizar($id, $request->validated());

        return redirect()->route('caracteristicas.index')
            ->with('success', 'Característica actualizada exitosamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->eliminar($id);

        return redirect()->route('caracteristicas.index')
            ->with('success', 'Característica eliminada exitosamente.');
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