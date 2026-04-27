<?php

namespace App\Http\Controllers;

use App\Http\Requests\FactorRequest;
use App\Models\StatusCna;
use App\Models\User;
use App\Models\Factor;
use App\Services\FactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


/**
 * Principio: Responsabilidad Única — solo orquesta, no valida ni persiste.
 * Principio: Inversión de Dependencias — depende del servicio, no del modelo.
 */
class FactorController extends Controller
{
    public function __construct(private readonly FactorService $service) {}

    public function index(): View
    {
        $this->authorize('viewAny', Factor::class);

        return view('VistaFactores.Index', [
            'factores' => $this->service->listar(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Factor::class);

        return view('VistaFactores.Create', $this->formData());
    }

    public function store(FactorRequest $request): RedirectResponse
    {
        $this->authorize('create', Factor::class);

        $this->service->crear($request->validated());

        return redirect()->route('factores.index')
            ->with('success', 'Factor creado exitosamente.');
    }

    public function edit(int $id): View
    {
        $factor = $this->service->obtener($id);

        $this->authorize('update', $factor);


        return view('VistaFactores.Edit', array_merge(
            $this->formData(),
            ['factor' => $this->service->obtener($id)]
        ));
    }

    public function update(FactorRequest $request, int $id): RedirectResponse
    {
        $factor = $this->service->obtener($id);
        $this->authorize('update', $factor);

        $this->service->actualizar($id, $request->validated());

        return redirect()->route('factores.index')
            ->with('success', 'Factor actualizado exitosamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $factor = $this->service->obtener($id);
        $this->authorize('delete', $factor);

        $this->service->eliminar($id);

        return redirect()->route('factores.index')
            ->with('success', 'Factor eliminado exitosamente.');
    }

    /* ── Datos compartidos para create/edit ── */
    private function formData(): array
    {
        return [
            'statuses'     => StatusCna::all(),
            'responsables' => User::orderBy('name')->get(),
        ];
    }
}
