@extends('administrator.app')

@section('title', 'Evaluación: ' . $caracteristica->name)

@section('content')

@php
    $estadoClases  = [1 => 'borrador', 2 => 'revision', 3 => 'aprobado', 4 => 'rechazado'];
    $estadoIconos  = [1 => 'bi-file-earmark', 2 => 'bi-hourglass-split', 3 => 'bi-check-circle-fill', 4 => 'bi-x-circle-fill'];
    $estadoLabels  = [1 => 'Borrador', 2 => 'En revisión', 3 => 'Aprobado', 4 => 'Rechazado'];
    $histColores   = [
        'iniciado'   => ['#2980b9', 'bi-send-fill'],
        'aprobado'   => ['#1a6b3a', 'bi-check-circle-fill'],
        'rechazado'  => ['#c0392b', 'bi-x-circle-fill'],
        'avanzado'   => ['var(--primary)', 'bi-arrow-right-circle-fill'],
        'reiniciado' => ['#e67e22', 'bi-arrow-counterclockwise'],
    ];
    $rolUsuario = auth()->user()->id_rol;

    // Stats globales de la característica
    $todasEv    = $caracteristica->aspectos->flatMap->evidencias;
    $totalEv    = $todasEv->count();
    $aprobadas  = $todasEv->where('estado_actual', 3)->count();
    $enRevision = $todasEv->where('estado_actual', 2)->count();
    $rechazadas = $todasEv->where('estado_actual', 4)->count();
    $borradores = $todasEv->where('estado_actual', 1)->count();
    $pct        = $totalEv > 0 ? round(($aprobadas / $totalEv) * 100) : 0;
@endphp

{{-- Breadcrumb --}}
<div class="gf-breadcrumb">
    <a href="{{ route('caracteristicas.index') }}">Características</a>
    <span class="gf-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:10px;"></i></span>
    <span>{{ $caracteristica->name }}</span>
</div>

{{-- Alertas --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert"
         style="border-radius:8px;font-size:14px;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert"
         style="border-radius:8px;font-size:14px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Header card --}}
<div class="gf-card mb-4" style="padding:20px 24px;">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div style="flex:1;min-width:0;">
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <span class="gf-page-title" style="margin-bottom:0;">{{ $caracteristica->name }}</span>
                <span class="gf-status gf-status-{{ strtolower(str_replace(' ', '-', $caracteristica->status?->name ?? 'activo')) }}">
                    {{ $caracteristica->status?->name ?? '—' }}
                </span>
            </div>
            <div class="gf-page-sub" style="margin-bottom:14px;">
                Factor: <strong>{{ $caracteristica->factor?->name ?? '—' }}</strong>
                &nbsp;·&nbsp;
                Responsable: <strong>{{ $caracteristica->responsableUser?->name ?? '—' }}</strong>
                &nbsp;·&nbsp;
                {{ $caracteristica->fecha_inicio?->format('d/m/Y') }} – {{ $caracteristica->fecha_fin?->format('d/m/Y') }}
            </div>

            {{-- Badges de resumen --}}
            <div class="d-flex gap-2 flex-wrap mb-3">
                <span style="font-size:12px;background:var(--primary-light);color:var(--primary);
                             padding:4px 10px;border-radius:20px;font-weight:600;">
                    {{ $totalEv }} evidencias
                </span>
                @if($aprobadas > 0)
                    <span class="gf-status gf-status-aprobado">
                        <i class="bi bi-check-circle-fill me-1"></i>{{ $aprobadas }} aprobadas
                    </span>
                @endif
                @if($enRevision > 0)
                    <span class="gf-status gf-status-revision">
                        <i class="bi bi-hourglass-split me-1"></i>{{ $enRevision }} en revisión
                    </span>
                @endif
                @if($rechazadas > 0)
                    <span class="gf-status gf-status-rechazado">
                        <i class="bi bi-x-circle-fill me-1"></i>{{ $rechazadas }} rechazadas
                    </span>
                @endif
                @if($borradores > 0)
                    <span class="gf-status gf-status-borrador">
                        <i class="bi bi-file-earmark me-1"></i>{{ $borradores }} en borrador
                    </span>
                @endif
            </div>

            {{-- Barra de progreso --}}
            @if($totalEv > 0)
                <div style="max-width:380px;">
                    <div style="background:var(--gray-100);border-radius:6px;height:8px;overflow:hidden;">
                        <div style="height:8px;background:linear-gradient(90deg,var(--primary),var(--primary-mid));
                                    width:{{ $pct }}%;transition:width .4s ease;"></div>
                    </div>
                    <div style="font-size:11px;color:var(--gray-500);margin-top:4px;">
                        {{ $pct }}% completado ({{ $aprobadas }}/{{ $totalEv }} aprobadas)
                    </div>
                </div>
            @endif
        </div>

        <a href="{{ route('caracteristicas.index') }}" class="gf-btn gf-btn-outline" style="height:34px;flex-shrink:0;">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

{{-- Secciones por aspecto --}}
@forelse($caracteristica->aspectos as $aspecto)
@php
    // Override por aspecto; si no existe, hereda el default de la característica
    $flujoEfectivo = $aspecto->flujoActivo ?? $caracteristica->flujoActivo;
    $pasosFlujo    = $flujoEfectivo?->pasos ?? collect();
    $totalPasos    = $pasosFlujo->count();
    $flujoEsDefault = $flujoEfectivo && is_null($flujoEfectivo->id_aspecto);
@endphp

<div class="gf-card mb-3 p-0" style="overflow:hidden;">

    {{-- Cabecera del aspecto --}}
    <div style="background:var(--primary);padding:12px 20px;
                display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <div>
            <span style="color:#fff;font-weight:600;font-size:14px;">
                <i class="bi bi-layers me-2"></i>{{ $aspecto->name }}
            </span>
            @if($flujoEfectivo)
                <span style="font-size:11px;color:rgba(255,255,255,0.75);margin-left:10px;">
                    <i class="bi bi-diagram-3"></i>
                    {{ $flujoEfectivo->nombre }} ({{ $totalPasos }} {{ $totalPasos == 1 ? 'paso' : 'pasos' }})
                    @if($flujoEsDefault)
                        <span style="opacity:.6;">· heredado</span>
                    @endif
                </span>
            @else
                <span style="font-size:11px;color:rgba(255,255,255,0.55);margin-left:10px;">
                    <i class="bi bi-exclamation-circle"></i> Sin flujo configurado
                </span>
            @endif
        </div>
        <span style="font-size:11px;color:rgba(255,255,255,0.7);">
            Responsable: {{ $aspecto->responsableUser?->name ?? '—' }}
        </span>
    </div>

    {{-- Evidencias --}}
    @if($aspecto->evidencias->isEmpty())
        <div style="padding:28px;text-align:center;color:var(--gray-400);font-size:13px;">
            <i class="bi bi-folder2-open" style="font-size:22px;display:block;margin-bottom:6px;"></i>
            No hay evidencias registradas para este aspecto.
            <a href="{{ route('evidencias.create') }}"
               style="color:var(--primary-mid);display:block;margin-top:4px;font-size:12px;">
                Registrar evidencia
            </a>
        </div>
    @else
        <table class="gf-table">
            <thead>
                <tr>
                    <th>Evidencia</th>
                    <th style="text-align:center;">Estado</th>
                    <th>Dónde está / Quién la tiene</th>
                    <th>Registrado por</th>
                    <th style="text-align:center;">Historial</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

            @foreach($aspecto->evidencias as $evidencia)
            @php
                $estado       = $evidencia->estado_actual ?? 1;
                $claseEstado  = $estadoClases[$estado] ?? 'borrador';
                $iconoEstado  = $estadoIconos[$estado] ?? 'bi-file-earmark';
                $labelEstado  = $estadoLabels[$estado] ?? 'Borrador';

                // Ejecución más reciente (ya ordenada desc en el repositorio)
                $ejecucion      = $evidencia->flujoEjecuciones->first();
                $pasoActual     = $ejecucion?->pasoActual;
                $historialEv    = $ejecucion?->historial ?? collect();
                $ejecucionActiva = $ejecucion && is_null($ejecucion->finalizado_at);

                // Pasos aprobados en esta ejecución
                $pasosAprobados = $historialEv->where('decision', 'aprobado')->pluck('id_paso')->unique();

                // ¿Puede el usuario actual tomar decisión en el paso activo?
                $puedeAprobar = $ejecucionActiva
                                && $pasoActual
                                && $pasoActual->rol_requerido == $rolUsuario;

                $collapseId = 'detalle-' . $evidencia->id_evidencia;
            @endphp

            {{-- Fila principal --}}
            <tr>
                {{-- Nombre + fechas --}}
                <td style="font-weight:500;max-width:220px;">
                    {{ $evidencia->nombre }}
                    @if($evidencia->fecha_inicio || $evidencia->fecha_fin)
                        <div style="font-size:11px;color:var(--gray-500);font-weight:400;margin-top:2px;">
                            <i class="bi bi-calendar3" style="font-size:10px;"></i>
                            {{ $evidencia->fecha_inicio?->format('d/m/Y') }}
                            @if($evidencia->fecha_fin)
                                – {{ $evidencia->fecha_fin?->format('d/m/Y') }}
                            @endif
                        </div>
                    @endif
                </td>

                {{-- Estado --}}
                <td style="text-align:center;">
                    <span class="gf-status gf-status-{{ $claseEstado }}">
                        <i class="bi {{ $iconoEstado }} me-1"></i>{{ $labelEstado }}
                    </span>
                </td>

                {{-- Dónde está / Quién la tiene --}}
                <td style="min-width:200px;">
                    @if($estado == 1)
                        @if(!$aspecto->flujoActivo)
                            <span style="font-size:12px;color:var(--gray-400);">
                                <i class="bi bi-exclamation-circle me-1"></i>Sin flujo asignado al aspecto
                            </span>
                        @else
                            <span style="font-size:12px;color:var(--gray-500);">
                                <i class="bi bi-send me-1"></i>Pendiente de envío a revisión
                            </span>
                        @endif

                    @elseif($estado == 2 && $pasoActual)
                        {{-- Mini stepper: círculos compactos --}}
                        <div style="display:flex;align-items:center;gap:3px;margin-bottom:5px;flex-wrap:wrap;">
                            @foreach($pasosFlujo as $p)
                                @php
                                    $sDone    = $pasosAprobados->contains($p->id_paso);
                                    $sCurrent = $pasoActual->id_paso == $p->id_paso;
                                @endphp
                                @if(!$loop->first)
                                    <div style="width:10px;height:1px;background:{{ $sDone ? 'var(--primary)' : 'var(--gray-200)' }};flex-shrink:0;"></div>
                                @endif
                                <div title="Paso {{ $p->orden }}: {{ $p->rolRequerido?->name ?? '—' }}"
                                     style="width:18px;height:18px;border-radius:50%;display:flex;align-items:center;
                                            justify-content:center;font-size:9px;font-weight:700;flex-shrink:0;
                                            background:{{ $sDone ? 'var(--primary)' : ($sCurrent ? 'var(--primary-mid,#6b7ed4)' : 'var(--gray-100)') }};
                                            color:{{ ($sDone || $sCurrent) ? '#fff' : 'var(--gray-400)' }};
                                            border:2px solid {{ $sDone ? 'var(--primary)' : ($sCurrent ? 'var(--primary-mid,#6b7ed4)' : 'var(--gray-200)') }};
                                            box-shadow:{{ $sCurrent ? '0 0 0 3px rgba(74,93,163,.2)' : 'none' }};">
                                    @if($sDone)<i class="bi bi-check" style="font-size:10px;"></i>@else{{ $p->orden }}@endif
                                </div>
                            @endforeach
                        </div>
                        {{-- Quién la tiene --}}
                        <div style="font-size:12px;font-weight:600;color:var(--primary);">
                            <i class="bi bi-person-fill me-1"></i>Esperando:
                            {{ $pasoActual->rolRequerido?->name ?? 'Rol desconocido' }}
                        </div>
                        <div style="font-size:10px;color:var(--gray-500);margin-top:1px;">
                            Paso {{ $pasoActual->orden }}{{ $totalPasos > 0 ? ' de '.$totalPasos : '' }}
                        </div>

                    @elseif($estado == 2 && !$pasoActual)
                        <span style="font-size:12px;color:var(--gray-500);">
                            <i class="bi bi-hourglass-split me-1"></i>En revisión
                        </span>

                    @elseif($estado == 3)
                        <span style="font-size:12px;font-weight:600;color:#1a6b3a;">
                            <i class="bi bi-check2-all me-1"></i>Flujo completado
                        </span>

                    @elseif($estado == 4)
                        <span style="font-size:12px;color:#c0392b;">
                            <i class="bi bi-x-circle me-1"></i>Rechazado — pendiente corrección
                        </span>
                    @endif
                </td>

                {{-- Registrado por --}}
                <td style="font-size:12px;color:var(--gray-600);">
                    {{ $evidencia->createdBy?->name ?? '—' }}
                </td>

                {{-- Botón expandir historial --}}
                <td style="text-align:center;">
                    @if($ejecucion && $historialEv->isNotEmpty())
                        <button class="gf-btn gf-btn-outline"
                                style="height:28px;padding:0 10px;font-size:11px;"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $collapseId }}"
                                aria-expanded="false"
                                title="Ver detalle del flujo">
                            <i class="bi bi-clock-history me-1"></i>{{ $historialEv->count() }}
                        </button>
                    @else
                        <span style="color:var(--gray-300);font-size:12px;">—</span>
                    @endif
                </td>

                {{-- Acciones --}}
                <td>
                    <div class="d-flex gap-1 flex-wrap">
                        {{-- En revisión + rol correcto → Aprobar / Rechazar --}}
                        @if($estado == 2 && $puedeAprobar)
                            <form action="{{ route('flujo.decision', $evidencia->id_evidencia) }}" method="POST">
                                @csrf
                                <input type="hidden" name="decision" value="aprobado">
                                <button type="submit" class="gf-btn"
                                        style="height:30px;padding:0 10px;font-size:11px;
                                               background:#1a6b3a;color:#fff;border:none;"
                                        onclick="return confirm('¿Aprobar esta evidencia?')">
                                    <i class="bi bi-check-lg"></i> Aprobar
                                </button>
                            </form>
                            <button type="button"
                                    class="gf-btn gf-btn-danger"
                                    style="height:30px;padding:0 10px;font-size:11px;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalRechazar{{ $evidencia->id_evidencia }}">
                                <i class="bi bi-x-lg"></i> Rechazar
                            </button>
                        @endif

                        {{-- Rechazado → Reiniciar (solo el creador) --}}
                        @if($estado == 4 && auth()->id() == $evidencia->created_by)
                            <form action="{{ route('flujo.reiniciar', $evidencia->id_evidencia) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Reiniciar el flujo? La evidencia volverá a revisión desde el primer paso.')">
                                @csrf
                                <button type="submit" class="gf-btn gf-btn-outline"
                                        style="height:30px;padding:0 10px;font-size:11px;">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reiniciar
                                </button>
                            </form>
                        @endif

                        {{-- Editar (solo en Borrador o Rechazado) --}}
                        @if(in_array($estado, [1, 4]))
                            <a href="{{ route('evidencias.edit', $evidencia->id_evidencia) }}"
                               class="gf-btn gf-btn-outline"
                               style="height:30px;padding:0 10px;font-size:11px;"
                               title="Editar evidencia">
                                <i class="bi bi-pencil"></i>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>

            {{-- Fila colapsable: Stepper completo + Historial --}}
            @if($ejecucion)
            <tr>
                <td colspan="6" style="padding:0;border-top:none;">
                    <div class="collapse" id="{{ $collapseId }}">
                        <div style="padding:16px 20px 20px;background:var(--gray-50,#f8f9fb);
                                    border-top:1px solid var(--gray-100);">

                            {{-- Stepper visual completo --}}
                            @if($pasosFlujo->isNotEmpty())
                            <div style="margin-bottom:20px;">
                                <div style="font-size:11px;font-weight:700;color:var(--gray-500);
                                            text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;">
                                    <i class="bi bi-diagram-3 me-1"></i>Progreso del flujo
                                </div>
                                <div style="display:flex;align-items:flex-start;overflow-x:auto;padding-bottom:6px;">

                                    {{-- Nodo inicio --}}
                                    <div style="display:flex;flex-direction:column;align-items:center;width:64px;flex-shrink:0;">
                                        <div style="width:32px;height:32px;border-radius:50%;background:#e0e7ff;
                                                    display:flex;align-items:center;justify-content:center;margin-bottom:6px;">
                                            <i class="bi bi-play-fill" style="color:var(--primary);font-size:13px;"></i>
                                        </div>
                                        <div style="font-size:10px;color:var(--gray-500);text-align:center;font-weight:600;">
                                            Inicio
                                        </div>
                                    </div>

                                    @foreach($pasosFlujo as $p)
                                    @php
                                        $sDone    = $pasosAprobados->contains($p->id_paso);
                                        $sCurrent = $ejecucionActiva && $pasoActual && $pasoActual->id_paso == $p->id_paso;
                                        $hEntry   = $historialEv->where('id_paso', $p->id_paso)
                                                                 ->sortByDesc('fecha')->first();
                                    @endphp
                                    {{-- Conector --}}
                                    <div style="width:36px;height:2px;margin-top:15px;flex-shrink:0;
                                                background:{{ $sDone ? 'var(--primary)' : 'var(--gray-200)' }};"></div>

                                    {{-- Paso --}}
                                    <div style="display:flex;flex-direction:column;align-items:center;
                                                width:88px;flex-shrink:0;">
                                        <div style="width:32px;height:32px;border-radius:50%;display:flex;
                                                    align-items:center;justify-content:center;font-size:13px;
                                                    font-weight:700;margin-bottom:6px;
                                                    background:{{ $sDone ? 'var(--primary)' : ($sCurrent ? 'var(--primary-mid,#6b7ed4)' : 'var(--gray-100)') }};
                                                    color:{{ ($sDone || $sCurrent) ? '#fff' : 'var(--gray-400)' }};
                                                    border:3px solid {{ $sDone ? 'var(--primary)' : ($sCurrent ? 'var(--primary-mid,#6b7ed4)' : 'var(--gray-200)') }};
                                                    box-shadow:{{ $sCurrent ? '0 0 0 4px rgba(74,93,163,.15)' : 'none' }};">
                                            @if($sDone)
                                                <i class="bi bi-check-lg"></i>
                                            @elseif($sCurrent)
                                                <i class="bi bi-hourglass-split" style="font-size:12px;"></i>
                                            @else
                                                {{ $p->orden }}
                                            @endif
                                        </div>
                                        <div style="font-size:10px;font-weight:700;text-align:center;
                                                    color:{{ $sDone ? 'var(--primary)' : ($sCurrent ? 'var(--primary-mid,#6b7ed4)' : 'var(--gray-400)') }};">
                                            {{ $p->rolRequerido?->name ?? 'Paso '.$p->orden }}
                                        </div>
                                        @if($hEntry)
                                            <div style="font-size:9px;color:var(--gray-500);text-align:center;margin-top:3px;line-height:1.3;">
                                                {{ $hEntry->usuario?->name ?? '—' }}<br>
                                                {{ $hEntry->fecha?->format('d/m H:i') ?? '—' }}
                                            </div>
                                        @elseif($sCurrent)
                                            <div style="font-size:9px;color:var(--primary-mid,#6b7ed4);
                                                        text-align:center;margin-top:3px;font-style:italic;">
                                                Pendiente
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach

                                    {{-- Conector final --}}
                                    <div style="width:36px;height:2px;margin-top:15px;flex-shrink:0;
                                                background:{{ $estado == 3 ? '#1a6b3a' : 'var(--gray-200)' }};"></div>

                                    {{-- Nodo final --}}
                                    <div style="display:flex;flex-direction:column;align-items:center;width:64px;flex-shrink:0;">
                                        <div style="width:32px;height:32px;border-radius:50%;margin-bottom:6px;display:flex;
                                                    align-items:center;justify-content:center;
                                                    background:{{ $estado == 3 ? '#1a6b3a' : 'var(--gray-100)' }};
                                                    border:3px solid {{ $estado == 3 ? '#1a6b3a' : 'var(--gray-200)' }};">
                                            <i class="bi {{ $estado == 3 ? 'bi-check-all' : 'bi-flag' }}"
                                               style="font-size:13px;color:{{ $estado == 3 ? '#fff' : 'var(--gray-400)' }};"></i>
                                        </div>
                                        <div style="font-size:10px;font-weight:700;text-align:center;
                                                    color:{{ $estado == 3 ? '#1a6b3a' : 'var(--gray-400)' }};">
                                            Aprobado
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endif

                            {{-- Timeline de historial --}}
                            @if($historialEv->isNotEmpty())
                            <div>
                                <div style="font-size:11px;font-weight:700;color:var(--gray-500);
                                            text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;">
                                    <i class="bi bi-clock-history me-1"></i>Historial de actividad
                                </div>
                                <div style="border-left:2px solid var(--gray-200);padding-left:18px;
                                            display:flex;flex-direction:column;gap:14px;">
                                    @foreach($historialEv as $h)
                                    @php
                                        [$hColor, $hIcon] = $histColores[$h->decision] ?? ['var(--gray-400)', 'bi-dot'];
                                    @endphp
                                    <div style="position:relative;">
                                        {{-- Bullet en la línea --}}
                                        <div style="position:absolute;left:-26px;top:2px;width:16px;height:16px;
                                                    border-radius:50%;background:{{ $hColor }};display:flex;
                                                    align-items:center;justify-content:center;">
                                            <i class="bi {{ $hIcon }}" style="font-size:8px;color:#fff;"></i>
                                        </div>

                                        {{-- Decisión + paso --}}
                                        <div style="font-size:12px;font-weight:700;color:{{ $hColor }};">
                                            {{ ucfirst($h->decision) }}
                                            @if($h->paso)
                                                <span style="font-weight:400;color:var(--gray-600);">
                                                    — Paso {{ $h->paso->orden }}
                                                    ({{ $h->paso->rolRequerido?->name ?? 'Rol' }})
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Usuario + fecha --}}
                                        <div style="font-size:11px;color:var(--gray-600);margin-top:2px;">
                                            <i class="bi bi-person me-1" style="color:var(--gray-400);"></i>
                                            <strong>{{ $h->usuario?->name ?? 'Sistema' }}</strong>
                                            <span style="color:var(--gray-400);margin-left:6px;">
                                                {{ $h->fecha?->format('d/m/Y H:i') ?? '—' }}
                                            </span>
                                        </div>

                                        {{-- Comentario (rechazo) --}}
                                        @if($h->comentario)
                                            <div style="font-size:11px;background:#fff;border:1px solid var(--gray-100);
                                                        border-left:3px solid {{ $hColor }};border-radius:0 6px 6px 0;
                                                        padding:6px 10px;margin-top:5px;color:var(--gray-700);">
                                                <i class="bi bi-chat-left-text me-1" style="color:var(--gray-400);"></i>
                                                {{ $h->comentario }}
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </td>
            </tr>
            @endif

            @endforeach

            </tbody>
        </table>
    @endif

</div>{{-- end gf-card aspecto --}}

{{-- Modales de rechazo (fuera de la tabla) --}}
@foreach($aspecto->evidencias as $evidencia)
@php
    $estado    = $evidencia->estado_actual ?? 1;
    $ejec      = $evidencia->flujoEjecuciones->first();
    $puedeModal = $ejec
                  && $ejec->pasoActual
                  && $ejec->pasoActual->rol_requerido == $rolUsuario
                  && $estado == 2
                  && is_null($ejec->finalizado_at);
@endphp
@if($puedeModal)
<div class="modal fade" id="modalRechazar{{ $evidencia->id_evidencia }}"
     tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid var(--gray-100);padding:16px 20px;">
                <h5 class="modal-title" style="font-size:15px;font-weight:600;color:var(--primary);">
                    <i class="bi bi-x-circle me-2"></i>Rechazar evidencia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('flujo.decision', $evidencia->id_evidencia) }}" method="POST">
                @csrf
                <input type="hidden" name="decision" value="rechazado">
                <div class="modal-body" style="padding:20px;">
                    <p style="font-size:13px;color:var(--gray-600);margin-bottom:12px;">
                        Evidencia: <strong>{{ $evidencia->nombre }}</strong>
                    </p>
                    <label class="gf-label" for="comentario{{ $evidencia->id_evidencia }}">
                        Motivo del rechazo <span style="color:var(--danger-text,#c0392b)">*</span>
                    </label>
                    <textarea id="comentario{{ $evidencia->id_evidencia }}"
                              name="comentario"
                              rows="4"
                              class="gf-textarea"
                              placeholder="Explica qué debe corregirse..."
                              required></textarea>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--gray-100);padding:12px 20px;gap:8px;">
                    <button type="button" class="gf-btn gf-btn-outline" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="gf-btn gf-btn-danger">
                        <i class="bi bi-x-circle"></i> Confirmar rechazo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

@empty
    <div class="gf-card" style="text-align:center;padding:48px;color:var(--gray-400);">
        <i class="bi bi-layers" style="font-size:32px;display:block;margin-bottom:12px;"></i>
        Esta característica no tiene aspectos registrados.
    </div>
@endforelse

@endsection
