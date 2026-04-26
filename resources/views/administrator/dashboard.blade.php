@extends('administrator.app')

@section('title', 'Dashboard CNA')

@push('styles')
<style>
/* ── KPI cards ── */
.db-kpi {
    background: #fff; border: 1px solid var(--gray-100); border-radius: 12px;
    padding: 20px 22px 18px; position: relative; overflow: hidden; transition: box-shadow .15s;
}
.db-kpi:hover { box-shadow: 0 4px 16px rgba(12,68,124,.08); }
.db-kpi-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; margin-bottom: 12px;
}
.db-kpi-value {
    font-size: 32px; font-weight: 700; line-height: 1;
    letter-spacing: -.5px; color: var(--primary-dark); margin-bottom: 4px;
}
.db-kpi-label { font-size: 12px; color: var(--gray-400); font-weight: 500; text-transform: uppercase; letter-spacing: .4px; }
.db-kpi-sub   { font-size: 12px; color: var(--gray-600); margin-top: 6px; }
.db-kpi-accent { position: absolute; right: 0; top: 0; bottom: 0; width: 4px; border-radius: 0 12px 12px 0; }

/* ── Section title ── */
.db-section-title {
    font-size: 13px; font-weight: 600; color: var(--gray-600);
    text-transform: uppercase; letter-spacing: .6px;
    margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid var(--gray-100);
}

/* ── Progress ── */
.db-progress-track { height: 8px; background: var(--gray-100); border-radius: 99px; overflow: hidden; }
.db-progress-fill  { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--primary-mid), var(--primary)); transition: width .6s cubic-bezier(.4,0,.2,1); }
.db-progress-fill.warning { background: linear-gradient(90deg,#f59e0b,#d97706); }
.db-progress-fill.danger  { background: linear-gradient(90deg,#ef4444,#b91c1c); }

/* ── Donut center ── */
.db-chart-wrap     { position: relative; max-width: 200px; margin: 0 auto; }
.db-chart-center   { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); text-align: center; pointer-events: none; }
.db-chart-center-value { font-size: 26px; font-weight: 700; color: var(--primary-dark); }
.db-chart-center-label { font-size: 10px; color: var(--gray-400); text-transform: uppercase; }
.db-legend-dot { width: 10px; height: 10px; border-radius: 3px; display: inline-block; margin-right: 7px; flex-shrink: 0; }

/* ── Pending table ── */
.db-pending-table { width: 100%; border-collapse: collapse; }
.db-pending-table td { padding: 9px 8px; font-size: 13px; border-bottom: 1px solid var(--gray-100); }
.db-pending-table tr:last-child td { border-bottom: none; }
.db-pending-badge {
    display: inline-flex; align-items: center; justify-content: center;
    background: #fff3cd; color: #92400e; border: 1px solid #fbbf24;
    border-radius: 12px; font-size: 11px; font-weight: 600; padding: 2px 9px; min-width: 28px;
}

/* ── Factor detail ── */
.db-factor-row   { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--gray-100); }
.db-factor-row:last-child { border-bottom: none; }
.db-factor-name  { flex: 0 0 220px; font-size: 13px; font-weight: 500; color: var(--primary-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.db-factor-bar   { flex: 1; }
.db-factor-pct   { flex: 0 0 42px; text-align: right; font-size: 13px; font-weight: 600; }
.db-factor-count { flex: 0 0 70px; text-align: right; font-size: 11px; color: var(--gray-400); }

/* ── Scope badge ── */
.db-scope-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border);
    border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 500;
}
</style>
@endpush

@section('content')

@php
    $scope      = $metrics['scope'];
    $totalEv    = $metrics['totalEvidencias'];
    $aprobadas  = $metrics['evAprobadas'];
    $enRevision = $metrics['evEnRevision'];
    $rechazadas = $metrics['evRechazadas'];
    $borradores = $metrics['evBorradores'];
    $pctCalidad = $metrics['pctCalidad'];
    $cobertura  = $metrics['cobertura'];
    $cumpl      = $metrics['cumplimiento'];
    $pendientes = $metrics['responsablesPendientes'];
    $factDet    = $metrics['factoresDetalle'];

    $scopeLabel = ['global' => 'Vista global', 'lider' => 'Mis características', 'enlace' => 'Mis aspectos'][$scope];
    $scopeIcon  = ['global' => 'bi-globe', 'lider' => 'bi-grid', 'enlace' => 'bi-list-check'][$scope];

    $esGlobal = Gate::allows('dashboard.view-global');
@endphp

{{-- Header --}}
<div class="d-flex align-items-start justify-content-between mb-4">
    <div>
        <div class="gf-page-title">Dashboard CNA</div>
        <div class="gf-page-sub">Resumen ejecutivo del proceso de acreditación</div>
    </div>
    <span class="db-scope-badge">
        <i class="bi {{ $scopeIcon }}"></i> {{ $scopeLabel }}
    </span>
</div>

{{-- ══════════════════════════════════════
     FILA 1 — KPIs principales
     ══════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-md-3 col-sm-6">
        <div class="db-kpi">
            <div class="db-kpi-accent" style="background:var(--primary);"></div>
            <div class="db-kpi-icon" style="background:var(--primary-light);color:var(--primary);">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div class="db-kpi-value">{{ $pctCalidad }}<span style="font-size:18px;font-weight:500;">%</span></div>
            <div class="db-kpi-label">Calidad de evidencias</div>
            <div class="db-kpi-sub">{{ $aprobadas }} aprobadas de {{ $totalEv }}</div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="db-kpi">
            <div class="db-kpi-accent" style="background:#0891b2;"></div>
            <div class="db-kpi-icon" style="background:#e0f2fe;color:#0891b2;">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div class="db-kpi-value">{{ $cobertura['pct'] }}<span style="font-size:18px;font-weight:500;">%</span></div>
            <div class="db-kpi-label">Cobertura de aspectos</div>
            <div class="db-kpi-sub">{{ $cobertura['con_evidencia'] }} de {{ $cobertura['total'] }} con evidencia</div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="db-kpi">
            <div class="db-kpi-accent" style="background:#7c3aed;"></div>
            <div class="db-kpi-icon" style="background:#ede9fe;color:#7c3aed;">
                <i class="bi bi-folder2-open"></i>
            </div>
            <div class="db-kpi-value">{{ $totalEv }}</div>
            <div class="db-kpi-label">Total evidencias</div>
            <div class="db-kpi-sub">{{ $borradores }} en borrador · {{ $rechazadas }} rechazadas</div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        @php $urgente = $enRevision > 0; @endphp
        <div class="db-kpi">
            <div class="db-kpi-accent" style="background:{{ $urgente ? '#f59e0b' : '#10b981' }};"></div>
            <div class="db-kpi-icon" style="background:{{ $urgente ? '#fef3c7' : '#d1fae5' }};color:{{ $urgente ? '#d97706' : '#059669' }};">
                <i class="bi {{ $urgente ? 'bi-hourglass-split' : 'bi-check-all' }}"></i>
            </div>
            <div class="db-kpi-value" style="color:{{ $urgente ? '#d97706' : 'var(--primary-dark)' }};">{{ $enRevision }}</div>
            <div class="db-kpi-label">Pendientes de revisión</div>
            <div class="db-kpi-sub">
                @if($urgente)
                    <span style="color:#d97706;font-weight:500;"><i class="bi bi-exclamation-circle"></i> Requieren acción</span>
                @else
                    <span style="color:#059669;">Sin pendientes</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     FILA 2 — Cumplimiento por nivel
     ══════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="gf-card">
            <div class="db-section-title">
                <i class="bi bi-bar-chart-steps me-1"></i> Cumplimiento por nivel
            </div>
            <div class="row g-4">

                @if($esGlobal)
                <div class="col-md-4">
                    @php $fp = $cumpl['factores']['pct']; $fc = $fp >= 70 ? '' : ($fp >= 40 ? 'warning' : 'danger'); @endphp
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:13px;font-weight:500;"><i class="bi bi-diagram-3 me-1" style="color:var(--primary-mid);"></i>Factores</span>
                            <span style="font-size:12px;color:var(--gray-400);">{{ $cumpl['factores']['cumplidos'] }} / {{ $cumpl['factores']['total'] }}</span>
                        </div>
                        <div class="db-progress-track mb-2"><div class="db-progress-fill {{ $fc }}" style="width:{{ $fp }}%;"></div></div>
                        <div style="font-size:22px;font-weight:700;color:var(--primary-dark);">{{ $fp }}<span style="font-size:13px;font-weight:400;">%</span></div>
                        <div style="font-size:11px;color:var(--gray-400);">Factores completamente cubiertos</div>
                    </div>
                </div>
                @endif

                <div class="col-md-{{ $esGlobal ? '4' : '6' }}">
                    @php $cp = $cumpl['caracteristicas']['pct']; $cc = $cp >= 70 ? '' : ($cp >= 40 ? 'warning' : 'danger'); @endphp
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:13px;font-weight:500;"><i class="bi bi-grid me-1" style="color:#0891b2;"></i>Características</span>
                            <span style="font-size:12px;color:var(--gray-400);">{{ $cumpl['caracteristicas']['cumplidas'] }} / {{ $cumpl['caracteristicas']['total'] }}</span>
                        </div>
                        <div class="db-progress-track mb-2"><div class="db-progress-fill {{ $cc }}" style="width:{{ $cp }}%;"></div></div>
                        <div style="font-size:22px;font-weight:700;color:var(--primary-dark);">{{ $cp }}<span style="font-size:13px;font-weight:400;">%</span></div>
                        <div style="font-size:11px;color:var(--gray-400);">Con todos sus aspectos evaluados</div>
                    </div>
                </div>

                <div class="col-md-{{ $esGlobal ? '4' : '6' }}">
                    @php $ap = $cumpl['aspectos']['pct']; $ac = $ap >= 70 ? '' : ($ap >= 40 ? 'warning' : 'danger'); @endphp
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:13px;font-weight:500;"><i class="bi bi-list-check me-1" style="color:#7c3aed;"></i>Aspectos evaluados</span>
                            <span style="font-size:12px;color:var(--gray-400);">{{ $cumpl['aspectos']['evaluados'] }} / {{ $cumpl['aspectos']['total'] }}</span>
                        </div>
                        <div class="db-progress-track mb-2"><div class="db-progress-fill {{ $ac }}" style="width:{{ $ap }}%;"></div></div>
                        <div style="font-size:22px;font-weight:700;color:var(--primary-dark);">{{ $ap }}<span style="font-size:13px;font-weight:400;">%</span></div>
                        <div style="font-size:11px;color:var(--gray-400);">Con al menos 1 evidencia aprobada</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     FILA 3 — Dona + Responsables
     ══════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-lg-5">
        <div class="gf-card h-100">
            <div class="db-section-title"><i class="bi bi-pie-chart me-1"></i> Evidencias por estado</div>

            @if($totalEv > 0)
                <div class="db-chart-wrap mb-4">
                    <canvas id="chartEstados" width="200" height="200"></canvas>
                    <div class="db-chart-center">
                        <div class="db-chart-center-value">{{ $totalEv }}</div>
                        <div class="db-chart-center-label">Total</div>
                    </div>
                </div>
                @php
                    $estadosLegend = [
                        ['Aprobadas',   $aprobadas,  '#10b981'],
                        ['En revisión', $enRevision, '#f59e0b'],
                        ['Borrador',    $borradores, '#94a3b8'],
                        ['Rechazadas',  $rechazadas, '#ef4444'],
                    ];
                @endphp
                <div style="display:flex;flex-direction:column;gap:9px;">
                    @foreach($estadosLegend as [$lbl, $val, $color])
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;">
                            <span class="db-legend-dot" style="background:{{ $color }};"></span>
                            <span style="font-size:13px;color:var(--gray-600);">{{ $lbl }}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:80px;height:5px;background:var(--gray-100);border-radius:99px;overflow:hidden;">
                                <div style="width:{{ $totalEv > 0 ? round($val/$totalEv*100) : 0 }}%;height:100%;background:{{ $color }};border-radius:99px;"></div>
                            </div>
                            <span style="font-size:13px;font-weight:600;color:var(--primary-dark);min-width:24px;text-align:right;">{{ $val }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:40px 0;color:var(--gray-400);">
                    <i class="bi bi-folder2-open" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                    No hay evidencias registradas aún.
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-7">
        <div class="gf-card h-100">
            <div class="db-section-title"><i class="bi bi-person-exclamation me-1"></i> Responsables con evidencias pendientes de revisión</div>

            @if($pendientes->isNotEmpty())
            <table class="db-pending-table">
                <thead>
                    <tr style="border-bottom:2px solid var(--gray-100);">
                        <td style="font-size:11px;font-weight:600;color:var(--gray-400);text-transform:uppercase;padding-bottom:8px;">Característica</td>
                        <td style="font-size:11px;font-weight:600;color:var(--gray-400);text-transform:uppercase;padding-bottom:8px;">Responsable</td>
                        <td style="font-size:11px;font-weight:600;color:var(--gray-400);text-transform:uppercase;padding-bottom:8px;text-align:center;">Pendientes</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendientes as $p)
                    <tr>
                        <td>
                            <div style="font-weight:500;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $p->caracteristica_nombre }}">
                                {{ $p->caracteristica_nombre }}
                            </div>
                        </td>
                        <td>
                            @if($p->responsable_nombre)
                            <div style="display:flex;align-items:center;gap:7px;">
                                <div style="width:28px;height:28px;border-radius:50%;background:var(--primary-light);color:var(--primary);
                                            display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;flex-shrink:0;">
                                    {{ strtoupper(substr($p->responsable_nombre,0,1)) }}{{ strtoupper(substr($p->responsable_apellido ?? '',0,1)) }}
                                </div>
                                <span style="font-size:13px;">{{ $p->responsable_nombre }} {{ $p->responsable_apellido }}</span>
                            </div>
                            @else
                                <span style="color:var(--gray-400);font-size:12px;">Sin asignar</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <span class="db-pending-badge">{{ $p->total_pendientes }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center;padding:40px 0;">
                <i class="bi bi-check-circle" style="font-size:32px;display:block;margin-bottom:10px;color:#10b981;"></i>
                <span style="color:#059669;font-weight:500;">Sin pendientes de revisión</span>
                <div style="font-size:12px;margin-top:4px;color:var(--gray-400);">Todas las evidencias están al día.</div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     FILA 4 — Progreso por factor (Admin/Director)
     ══════════════════════════════════════ --}}
@can('dashboard.view-global')
@if($factDet->isNotEmpty())
<div class="gf-card">
    <div class="db-section-title"><i class="bi bi-diagram-3 me-1"></i> Progreso por factor</div>
    @foreach($factDet as $f)
    @php
        $fp = $f->pct;
        $barBg  = $fp >= 70 ? 'linear-gradient(90deg,#10b981,#059669)' : ($fp >= 40 ? 'linear-gradient(90deg,#f59e0b,#d97706)' : 'linear-gradient(90deg,#ef4444,#b91c1c)');
        $txtClr = $fp >= 70 ? '#059669' : ($fp >= 40 ? '#d97706' : '#b91c1c');
    @endphp
    <div class="db-factor-row">
        <div class="db-factor-name" title="{{ $f->name }}">{{ $f->name }}</div>
        <div class="db-factor-bar">
            <div class="db-progress-track">
                <div style="height:100%;width:{{ $fp }}%;border-radius:99px;background:{{ $barBg }};transition:width .6s;"></div>
            </div>
        </div>
        <div class="db-factor-pct" style="color:{{ $txtClr }};">{{ $fp }}%</div>
        <div class="db-factor-count">{{ $f->asp_evaluados }}/{{ $f->total_aspectos }} asp.</div>
    </div>
    @endforeach
</div>
@endif
@endcan

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const canvas = document.getElementById('chartEstados');
    if (!canvas) return;
    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: ['Aprobadas', 'En revisión', 'Borrador', 'Rechazadas'],
            datasets: [{
                data: [{{ $aprobadas }}, {{ $enRevision }}, {{ $borradores }}, {{ $rechazadas }}],
                backgroundColor: ['#10b981', '#f59e0b', '#94a3b8', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 6,
            }],
        },
        options: {
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} evidencias` },
                },
            },
            animation: { animateRotate: true, duration: 700 },
        },
    });
})();
</script>
@endpush
