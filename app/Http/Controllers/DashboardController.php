<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

/**
 * Responsabilidad: orquestar el dashboard; toda la lógica vive en DashboardService.
 *
 * Control de acceso (Gates definidos en AppServiceProvider):
 *   'dashboard.view'        → todos los usuarios autenticados
 *   'dashboard.view-global' → Admin y Director (métricas globales + detalle por factor)
 *
 * El servicio decide qué datos devuelve según el rol del usuario autenticado:
 *   Admin / Director        → métricas globales, detalle por factor, tabla de responsables completa
 *   Líder de Característica → métricas de sus características y sus aspectos
 *   Enlace                  → métricas de sus aspectos
 */
class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $service) {}

    public function index(): View
    {
        // Verificar que el usuario puede acceder al dashboard
        $this->authorize('dashboard.view');

        $user    = auth()->user();
        $metrics = $this->service->getMetrics($user);

        return view('administrator.dashboard', compact('metrics', 'user'));
    }
}
