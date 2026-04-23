<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvidenciaRequest;
use App\Models\Aspecto;
use App\Models\Evidencia;
use App\Models\EstadoDocumento;
use App\Models\StatusCna;
use App\Services\EvidenciaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Principio: Responsabilidad Única — solo orquesta, no valida ni persiste.
 * Principio: Inversión de Dependencias — depende del servicio, no del modelo.
 */
class EvidenciaController extends Controller
{
    public function __construct(private readonly EvidenciaService $service) {}

    public function index(): View
    {
        $this->authorize('viewAny', Evidencia::class);

        return view('VistaEvidencias.Index', [
            'evidencias' => $this->service->listar(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Evidencia::class);

        return view('VistaEvidencias.Create', $this->formData());
    }

    public function store(EvidenciaRequest $request): RedirectResponse
    {
        $this->authorize('create', Evidencia::class);

        $this->service->crear($request->validated());

        return redirect()->route('evidencias.index')
            ->with('success', 'Evidencia creada exitosamente.');
    }

    public function edit(int $id): View
    {
        $evidencia = $this->service->obtener($id);

        $this->authorize('update', $evidencia);

        return view('VistaEvidencias.Edit', array_merge(
            $this->formData(),
            ['evidencia' => $evidencia]
        ));
    }

    public function update(EvidenciaRequest $request, int $id): RedirectResponse
    {
        $evidencia = $this->service->obtener($id);
        $this->authorize('update', $evidencia);

        $this->service->actualizar($id, $request->validated());

        return redirect()->route('evidencias.index')
            ->with('success', 'Evidencia actualizada exitosamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->eliminar($id);

        return redirect()->route('evidencias.index')
            ->with('success', 'Evidencia eliminada exitosamente.');
    }

    /* ── Datos compartidos para create/edit ── */
    private function formData(): array
    {
        return [
            'statuses'  => StatusCna::all(),
            'aspectos'  => Aspecto::orderBy('name')->get(),
            'estados'   => EstadoDocumento::all(),
        ];
    }
}
