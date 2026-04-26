<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Solo lectura — no expone rutas de escritura.
 * Acceso restringido a Admin mediante gate en la ruta.
 */
class AuditoriaController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('dashboard.view-global'); // solo Admin / Director

        $query = Auditoria::with('usuario')
            ->orderByDesc('fecha_modificacion')
            ->orderByDesc('id_auditoria');

        // ── Filtros opcionales ──────────────────────────
        if ($objeto = $request->input('objeto')) {
            $query->where('objeto', $objeto);
        }

        if ($operacion = $request->input('operacion')) {
            $query->where('operacion', $operacion);
        }

        if ($registro = $request->input('registro')) {
            $query->where('registro', $registro);
        }

        if ($userId = $request->input('usuario_id')) {
            $query->where('modificado_por', $userId);
        }

        if ($desde = $request->input('fecha_desde')) {
            $query->whereDate('fecha_modificacion', '>=', $desde);
        }

        if ($hasta = $request->input('fecha_hasta')) {
            $query->whereDate('fecha_modificacion', '<=', $hasta);
        }

        $registros = $query->paginate(50)->withQueryString();

        // Listas para los selectores del filtro
        $objetos   = Auditoria::distinct()->orderBy('objeto')->pluck('objeto')->filter()->values();
        $usuarios  = User::orderBy('name')->get(['id', 'name', 'first_surname']);

        return view('administrator.auditoria', compact('registros', 'objetos', 'usuarios'));
    }
}
