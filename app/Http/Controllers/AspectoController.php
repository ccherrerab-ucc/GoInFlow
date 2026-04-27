<?php

namespace App\Http\Controllers;

use App\Http\Requests\AspectoRequest;
use App\Models\Aspecto;
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
        $this->authorize('viewAny', Aspecto::class);

        return view('VistaAspectos.Index', [
            'aspectos' => $this->service->listar(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Aspecto::class);

        return view('VistaAspectos.create', $this->formData());
    }

    public function store(AspectoRequest $request): RedirectResponse
    {
        $this->authorize('create', Aspecto::class);

        $this->service->crear($request->validated());

        return redirect()->route('aspectos.index')
            ->with('success', 'Aspecto creado exitosamente.');
    }

    public function edit(int $id): View
    {
        $aspecto = $this->service->obtener($id);
        $this->authorize('update', $aspecto);

        return view('VistaAspectos.edit', array_merge(
            $this->formData(),
            ['aspecto' => $aspecto]
        ));
    }

    public function update(AspectoRequest $request, int $id): RedirectResponse
    {
        $aspecto = $this->service->obtener($id);
        $this->authorize('update', $aspecto);

        $this->service->actualizar($id, $request->validated());

        return redirect()->route('aspectos.index')
            ->with('success', 'Aspecto actualizado exitosamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $aspecto = $this->service->obtener($id);
        $this->authorize('delete', $aspecto);

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
