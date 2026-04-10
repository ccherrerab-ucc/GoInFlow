<?php

namespace App\Http\Controllers;

use App\Http\Requests\AspectoRequest;
use App\Models\Caracteristica;
use App\Models\StatusCna;
use App\Models\User;
use App\Services\AspectoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AspectoController extends Controller
{
    public function __construct(private readonly AspectoService $service) {}

    public function index(): View
    {
        return view('VistaAspectos.Index', [
            'aspectos' => $this->service->listar(),
        ]);
    }

    public function create(): View
    {
        return view('VistaAspectos.create', $this->formData());
    }

    public function store(AspectoRequest $request): RedirectResponse
    {
        $this->service->crear($request->validated());

        return redirect()->route('aspectos.index')
            ->with('success', 'Aspecto creado exitosamente.');
    }

    public function edit(int $id): View
    {
        return view('VistaAspectos.edit', array_merge(
            $this->formData(),
            ['aspecto' => $this->service->obtener($id)]
        ));
    }

    public function update(AspectoRequest $request, int $id): RedirectResponse
    {
        $this->service->actualizar($id, $request->validated());

        return redirect()->route('VistaAspectos.Index')
            ->with('success', 'Aspecto actualizado exitosamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->eliminar($id);

        return redirect()->route('aspectos.index')
            ->with('success', 'Aspecto eliminado exitosamente.');
    }

    private function formData(): array
    {
        return [
            'caracteristicas' => Caracteristica::with('factor')->orderBy('name')->get(),
            'statuses'        => StatusCna::all(),
            'responsables'    => User::orderBy('name')->get(),
        ];
    }
}