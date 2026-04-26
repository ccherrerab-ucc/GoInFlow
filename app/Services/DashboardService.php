<?php

namespace App\Services;

use App\Models\Aspecto;
use App\Models\Caracteristica;
use App\Models\Evidencia;
use App\Models\Factor;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Punto de entrada único. El servicio resuelve qué métricas mostrar
     * según el rol: Admin/Director ven todo; Líder ve sus características;
     * Enlace ve sus aspectos.
     */
    public function getMetrics(User $user): array
    {
        $isGlobal = $user->isAdmin() || $user->isDirector();
        $aspIds   = $this->resolveAspIds($user);
        $carIds   = $this->resolveCarIds($user, $aspIds);

        $evStats = $this->evidenciaStats($aspIds);
        $totalEv = $evStats->sum();

        return [
            'scope'                  => $this->resolveScope($user),
            // KPIs principales
            'totalEvidencias'        => $totalEv,
            'evAprobadas'            => (int) $evStats->get(3, 0),
            'evEnRevision'           => (int) $evStats->get(2, 0),
            'evRechazadas'           => (int) $evStats->get(4, 0),
            'evBorradores'           => (int) $evStats->get(1, 0),
            'pctCalidad'             => $totalEv > 0 ? round($evStats->get(3, 0) / $totalEv * 100, 1) : 0,
            // Cobertura (aspectos con ≥1 evidencia en cualquier estado)
            'cobertura'              => $this->cobertura($aspIds),
            // Cumplimiento por nivel
            'cumplimiento'           => $this->cumplimientoNiveles($isGlobal, $aspIds, $carIds),
            // Para el gráfico de dona
            'evPorEstado'            => $evStats,
            // Tabla de responsables con evidencias pendientes de revisión
            'responsablesPendientes' => $this->responsablesPendientes($carIds, $isGlobal),
            // Detalle por factor (solo Admin/Director)
            'factoresDetalle'        => $isGlobal ? $this->factoresDetalle() : collect(),
        ];
    }

    // ──────────────────────────────────────────────
    // Resolución de scope
    // ──────────────────────────────────────────────

    private function resolveScope(User $user): string
    {
        if ($user->isAdmin() || $user->isDirector()) return 'global';
        if ($user->isLiderCaracteristica())           return 'lider';
        return 'enlace';
    }

    /**
     * null = sin filtro (global).
     * array = IDs de aspectos accesibles para el usuario.
     */
    private function resolveAspIds(User $user): ?array
    {
        if ($user->isAdmin() || $user->isDirector()) {
            return null;
        }
        if ($user->isLiderCaracteristica()) {
            $carIds = Caracteristica::where('responsable', $user->id)->pluck('id_caracteristica');
            return Aspecto::whereIn('caracteristica_id', $carIds)->pluck('id_aspecto')->toArray();
        }
        // Enlace: sus aspectos directos
        return Aspecto::where('responsable', $user->id)->pluck('id_aspecto')->toArray();
    }

    private function resolveCarIds(User $user, ?array $aspIds): ?array
    {
        if ($user->isAdmin() || $user->isDirector()) {
            return null;
        }
        if ($user->isLiderCaracteristica()) {
            return Caracteristica::where('responsable', $user->id)->pluck('id_caracteristica')->toArray();
        }
        if (empty($aspIds)) return [];
        return Aspecto::whereIn('id_aspecto', $aspIds)->pluck('caracteristica_id')->unique()->toArray();
    }

    // ──────────────────────────────────────────────
    // Evidencias
    // ──────────────────────────────────────────────

    /** Retorna Collection<estado_actual => count>. */
    private function evidenciaStats(?array $aspIds): Collection
    {
        $query = Evidencia::selectRaw('estado_actual, COUNT(*) as total')
            ->groupBy('estado_actual');

        if ($aspIds !== null) {
            $safe = empty($aspIds) ? [0] : $aspIds;
            $query->whereIn('id_aspecto', $safe);
        }

        return $query->pluck('total', 'estado_actual');
    }

    // ──────────────────────────────────────────────
    // Cobertura: aspectos con al menos 1 evidencia
    // ──────────────────────────────────────────────

    private function cobertura(?array $aspIds): array
    {
        $base = Aspecto::query();
        if ($aspIds !== null) {
            $base->whereIn('id_aspecto', empty($aspIds) ? [0] : $aspIds);
        }

        $total        = $base->count();
        $conEvidencia = (clone $base)->whereHas('evidencias')->count();

        return [
            'total'        => $total,
            'con_evidencia' => $conEvidencia,
            'pct'          => $total > 0 ? round($conEvidencia / $total * 100, 1) : 0,
        ];
    }

    // ──────────────────────────────────────────────
    // Cumplimiento por nivel
    // ──────────────────────────────────────────────

    /**
     * Definiciones:
     *   Aspecto evaluado      = tiene ≥1 evidencia aprobada (estado_actual = 3)
     *   Característica cumplida = todos sus aspectos están evaluados
     *   Factor cumplido        = todas sus características están cumplidas
     */
    private function cumplimientoNiveles(bool $isGlobal, ?array $aspIds, ?array $carIds): array
    {
        // ── Aspectos ──
        $aspBase = Aspecto::query();
        if ($aspIds !== null) {
            $aspBase->whereIn('id_aspecto', empty($aspIds) ? [0] : $aspIds);
        }
        $totalAsp     = $aspBase->count();
        $aspEvaluados = (clone $aspBase)
            ->whereHas('evidencias', fn($q) => $q->where('estado_actual', 3))
            ->count();
        $pctAspecto   = $totalAsp > 0 ? round($aspEvaluados / $totalAsp * 100, 1) : 0;

        // ── Características ──
        $carBase = Caracteristica::query();
        if ($carIds !== null) {
            $carBase->whereIn('id_caracteristica', empty($carIds) ? [0] : $carIds);
        }
        $totalCar = $carBase->count();

        // "No cumplida": no tiene aspectos O tiene aspectos sin evidencia aprobada
        $carNoCumplidas = (clone $carBase)->where(function ($q) {
            $q->whereDoesntHave('aspectos')
              ->orWhereHas('aspectos', function ($sq) {
                  $sq->whereDoesntHave('evidencias', fn($eq) => $eq->where('estado_actual', 3));
              });
        })->count();
        $carCumplidas = $totalCar - $carNoCumplidas;
        $pctCar       = $totalCar > 0 ? round($carCumplidas / $totalCar * 100, 1) : 0;

        // ── Factores ──
        if ($isGlobal) {
            $totalFac = Factor::count();
            $facNoCumplidos = Factor::where(function ($q) {
                $q->whereDoesntHave('caracteristicas')
                  ->orWhereHas('caracteristicas', function ($sq) {
                      $sq->whereDoesntHave('aspectos')
                         ->orWhereHas('aspectos', function ($aq) {
                             $aq->whereDoesntHave('evidencias', fn($eq) => $eq->where('estado_actual', 3));
                         });
                  });
            })->count();
            $facCumplidos = $totalFac - $facNoCumplidos;
            $pctFac       = $totalFac > 0 ? round($facCumplidos / $totalFac * 100, 1) : 0;
        } else {
            // Para roles acotados derivamos los factores de las características visibles
            $facIds       = Caracteristica::whereIn('id_caracteristica', empty($carIds) ? [0] : ($carIds ?? []))->pluck('factor_id')->unique();
            $totalFac     = $facIds->count();
            $facCumplidos = 0;
            $pctFac       = 0;
        }

        return [
            'factores' => [
                'total'     => $totalFac,
                'cumplidos' => $facCumplidos,
                'pct'       => $pctFac,
            ],
            'caracteristicas' => [
                'total'     => $totalCar,
                'cumplidas' => $carCumplidas,
                'pct'       => $pctCar,
            ],
            'aspectos' => [
                'total'      => $totalAsp,
                'evaluados'  => $aspEvaluados,
                'pct'        => $pctAspecto,
            ],
        ];
    }

    // ──────────────────────────────────────────────
    // Responsables con evidencias en revisión
    // ──────────────────────────────────────────────

    private function responsablesPendientes(?array $carIds, bool $isGlobal): Collection
    {
        $query = DB::table('evidencias as e')
            ->join('aspectos as a', 'e.id_aspecto', '=', 'a.id_aspecto')
            ->join('caracteristicas as c', 'a.caracteristica_id', '=', 'c.id_caracteristica')
            ->leftJoin('users as u', 'c.responsable', '=', 'u.id')
            ->where('e.estado_actual', 2)
            ->select(
                'c.id_caracteristica',
                'c.name as caracteristica_nombre',
                'u.name as responsable_nombre',
                'u.first_surname as responsable_apellido',
                DB::raw('COUNT(e.id_evidencia) as total_pendientes')
            )
            ->groupBy('c.id_caracteristica', 'c.name', 'u.name', 'u.first_surname')
            ->orderByDesc('total_pendientes');

        if (!$isGlobal && $carIds !== null) {
            $safe = empty($carIds) ? [0] : $carIds;
            $query->whereIn('c.id_caracteristica', $safe);
        }

        return $query->limit(8)->get();
    }

    // ──────────────────────────────────────────────
    // Detalle por factor (Admin/Director)
    // ──────────────────────────────────────────────

    private function factoresDetalle(): Collection
    {
        return DB::table('factors as f')
            ->leftJoin('caracteristicas as c', 'c.factor_id', '=', 'f.id_factor')
            ->leftJoin('aspectos as a', 'a.caracteristica_id', '=', 'c.id_caracteristica')
            ->leftJoin(
                DB::raw('(SELECT DISTINCT id_aspecto FROM evidencias WHERE estado_actual = 3) AS ev_apr'),
                'ev_apr.id_aspecto', '=', 'a.id_aspecto'
            )
            ->select(
                'f.id_factor',
                'f.name',
                DB::raw('COUNT(DISTINCT a.id_aspecto) AS total_aspectos'),
                DB::raw('COUNT(DISTINCT ev_apr.id_aspecto) AS asp_evaluados')
            )
            ->groupBy('f.id_factor', 'f.name')
            ->orderBy('f.id_factor')
            ->get()
            ->map(function ($row) {
                $row->pct = $row->total_aspectos > 0
                    ? round($row->asp_evaluados / $row->total_aspectos * 100)
                    : 0;
                return $row;
            });
    }
}
