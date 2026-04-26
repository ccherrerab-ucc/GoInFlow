@extends('administrator.app')

@section('title', 'Evaluación: ' . $caracteristica->name)

@section('content')

@php
    $estadoClases = [1 => 'borrador', 2 => 'revision', 3 => 'aprobado', 4 => 'rechazado'];
    $estadoIconos = [1 => 'bi-file-earmark', 2 => 'bi-hourglass-split', 3 => 'bi-check-circle-fill', 4 => 'bi-x-circle-fill'];
    $estadoLabels = [1 => 'Borrador', 2 => 'En revisión', 3 => 'Aprobado', 4 => 'Rechazado'];
    $histColores  = [
        'iniciado'   => ['#2980b9', 'bi-send-fill'],
        'aprobado'   => ['#1a6b3a', 'bi-check-circle-fill'],
        'rechazado'  => ['#c0392b', 'bi-x-circle-fill'],
        'avanzado'   => ['var(--primary)', 'bi-arrow-right-circle-fill'],
        'reiniciado' => ['#e67e22', 'bi-arrow-counterclockwise'],
    ];

    // ¿Es el usuario actual el responsable de esta característica?
    $esResponsable = auth()->id() === (int) $caracteristica->responsable;

    // Recopilar todas las evidencias en una colección plana con referencia al aspecto
    $todasEvidencias = $caracteristica->aspectos->flatMap(function ($aspecto) {
        return $aspecto->evidencias->map(function ($ev) use ($aspecto) {
            $ev->_aspecto = $aspecto;
            return $ev;
        });
    });

    $totalEv    = $todasEvidencias->count();
    $aprobadas  = $todasEvidencias->where('estado_actual', 3)->count();
    $enRevision = $todasEvidencias->where('estado_actual', 2)->count();
    $rechazadas = $todasEvidencias->where('estado_actual', 4)->count();
    $borradores = $todasEvidencias->where('estado_actual', 1)->count();
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

{{-- ── Header card ─────────────────────────────────────────── --}}
<div class="gf-card mb-4" style="padding:20px 24px;">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div style="flex:1;min-width:0;">
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <span class="gf-page-title" style="margin-bottom:0;">{{ $caracteristica->name }}</span>
                <span class="gf-status gf-status-{{ strtolower(str_replace(' ', '-', $caracteristica->status?->name ?? 'activo')) }}">
                    {{ $caracteristica->status?->name ?? '—' }}
                </span>
                @if($esResponsable)
                    <span style="font-size:11px;background:#fef3c7;color:#92400e;
                                 padding:2px 8px;border-radius:20px;font-weight:600;">
                        <i class="bi bi-shield-check me-1"></i>Eres el responsable
                    </span>
                @endif
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
                        {{ $pct }}% aprobado ({{ $aprobadas }}/{{ $totalEv }})
                    </div>
                </div>
            @endif
        </div>

        <a href="{{ route('caracteristicas.index') }}" class="gf-btn gf-btn-outline" style="height:34px;flex-shrink:0;">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

{{-- ── Tabla principal de evidencias ──────────────────────── --}}
<div class="gf-card p-0" style="overflow:hidden;">

    <div style="padding:14px 20px;border-bottom:1px solid var(--gray-100);
                display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <div style="font-size:14px;font-weight:700;color:var(--primary);">
            <i class="bi bi-folder2-open me-2"></i>Evidencias de la característica
        </div>
        @if($esResponsable)
            <div style="font-size:12px;color:var(--gray-500);">
                <i class="bi bi-info-circle me-1"></i>
                Puedes aprobar o rechazar evidencias en estado "En revisión"
            </div>
        @endif
    </div>

    @if($todasEvidencias->isEmpty())
        <div style="padding:48px;text-align:center;color:var(--gray-400);">
            <i class="bi bi-folder2-open" style="font-size:32px;display:block;margin-bottom:12px;"></i>
            No hay evidencias registradas en ninguno de los aspectos.
        </div>
    @else
        <table class="gf-table">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Evidencia</th>
                    <th>Aspecto</th>
                    <th style="text-align:center;">Estado</th>
                    <th>Descripción</th>
                    <th>Fecha de carga</th>
                    <th style="text-align:center;">Resultados</th>
                    <th style="text-align:center;">Historial</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

            @foreach($todasEvidencias as $evidencia)
            @php
                $estado       = $evidencia->estado_actual ?? 1;
                $claseEstado  = $estadoClases[$estado] ?? 'borrador';
                $iconoEstado  = $estadoIconos[$estado] ?? 'bi-file-earmark';
                $labelEstado  = $estadoLabels[$estado] ?? 'Borrador';
                $aspecto      = $evidencia->_aspecto;

                $ejecucion      = $evidencia->flujoEjecuciones->first();
                $ejecucionActiva = $ejecucion && is_null($ejecucion->finalizado_at);
                $historialEv    = $ejecucion?->historial ?? collect();

                // El responsable puede aprobar/rechazar si la evidencia está en revisión
                $puedeAprobar = $esResponsable && $estado === 2 && $ejecucionActiva;

                $tieneResultados = $evidencia->resultados->isNotEmpty();
                $collapseId      = 'detalle-' . $evidencia->id_evidencia;
            @endphp

            {{-- Fila principal --}}
            <tr>
                {{-- # --}}
                <td style="font-size:12px;color:var(--gray-500);">{{ $evidencia->id_evidencia }}</td>

                {{-- Evidencia --}}
                <td style="font-weight:500;max-width:200px;">
                    {{ $evidencia->nombre }}
                    @if($evidencia->nombre_archivo)
                        <div style="font-size:11px;color:var(--primary);margin-top:2px;">
                            <i class="bi bi-paperclip me-1"></i>{{ $evidencia->nombre_archivo }}
                        </div>
                    @endif
                </td>

                {{-- Aspecto --}}
                <td style="font-size:12px;max-width:160px;">
                    <span style="color:var(--gray-500);font-size:11px;display:block;">{{ $aspecto->name }}</span>
                </td>

                {{-- Estado --}}
                <td style="text-align:center;">
                    <span class="gf-status gf-status-{{ $claseEstado }}">
                        <i class="bi {{ $iconoEstado }} me-1"></i>{{ $labelEstado }}
                    </span>
                    @if($estado === 2 && $esResponsable)
                        <div style="font-size:10px;color:var(--primary);margin-top:3px;font-weight:600;">
                            <i class="bi bi-hand-index me-1"></i>Tu turno
                        </div>
                    @endif
                </td>

                {{-- Descripción --}}
                <td style="font-size:12px;color:var(--gray-600);max-width:180px;">
                    @if($evidencia->descripcion)
                        <span style="display:-webkit-box;-webkit-line-clamp:2;
                                     -webkit-box-orient:vertical;overflow:hidden;">
                            {{ $evidencia->descripcion }}
                        </span>
                    @else
                        <span style="color:var(--gray-300);">—</span>
                    @endif
                </td>

                {{-- Fecha de carga --}}
                <td style="font-size:12px;white-space:nowrap;color:var(--gray-600);">
                    {{ $evidencia->created_at?->format('d/m/Y') }}<br>
                    <span style="color:var(--gray-400);font-size:11px;">
                        {{ $evidencia->created_at?->format('H:i') }}
                    </span>
                </td>

                {{-- Asociada a resultados --}}
                <td style="text-align:center;">
                    @if($tieneResultados)
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;
                                     font-weight:600;color:#1a6b3a;background:#d1fae5;
                                     padding:3px 8px;border-radius:12px;">
                            <i class="bi bi-link-45deg"></i>Sí
                        </span>
                    @else
                        <span style="font-size:11px;color:var(--gray-300);">—</span>
                    @endif
                </td>

                {{-- Historial --}}
                <td style="text-align:center;">
                    @if($ejecucion && $historialEv->isNotEmpty())
                        <button class="gf-btn gf-btn-outline"
                                style="height:28px;padding:0 10px;font-size:11px;"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $collapseId }}"
                                aria-expanded="false">
                            <i class="bi bi-clock-history me-1"></i>{{ $historialEv->count() }}
                        </button>
                    @else
                        <span style="color:var(--gray-300);font-size:12px;">—</span>
                    @endif
                </td>

                {{-- Acciones --}}
                <td>
                    <div class="d-flex gap-1 flex-wrap">

                        {{-- Responsable: Aprobar (solo en revisión) --}}
                        @if($puedeAprobar)
                            <form action="{{ route('flujo.decision', $evidencia->id_evidencia) }}" method="POST">
                                @csrf
                                <input type="hidden" name="decision" value="aprobado">
                                <button type="submit"
                                        class="gf-btn"
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

                        {{-- Creador: Reiniciar tras rechazo --}}
                        @if($estado === 4 && auth()->id() === (int) $evidencia->created_by)
                            <form action="{{ route('flujo.reiniciar', $evidencia->id_evidencia) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Reiniciar el flujo? La evidencia volverá a revisión.')">
                                @csrf
                                <button type="submit" class="gf-btn gf-btn-outline"
                                        style="height:30px;padding:0 10px;font-size:11px;">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reiniciar
                                </button>
                            </form>
                        @endif

                        {{-- Creador: Editar (Borrador o Rechazado) --}}
                        @if(in_array($estado, [1, 4]) && auth()->id() === (int) $evidencia->created_by)
                            <a href="{{ route('evidencias.edit', $evidencia->id_evidencia) }}"
                               class="gf-btn gf-btn-outline"
                               style="height:30px;padding:0 10px;font-size:11px;"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        @endif

                    </div>
                </td>
            </tr>

            {{-- Fila colapsable: Historial de actividad --}}
            @if($ejecucion && $historialEv->isNotEmpty())
            <tr>
                <td colspan="9" style="padding:0;border-top:none;">
                    <div class="collapse" id="{{ $collapseId }}">
                        <div style="padding:16px 20px 20px;background:var(--gray-50,#f8f9fb);
                                    border-top:1px solid var(--gray-100);">

                            <div style="font-size:11px;font-weight:700;color:var(--gray-500);
                                        text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;">
                                <i class="bi bi-clock-history me-1"></i>Historial de actividad
                            </div>

                            <div style="border-left:2px solid var(--gray-200);padding-left:18px;
                                        display:flex;flex-direction:column;gap:12px;">
                                @foreach($historialEv as $h)
                                @php
                                    [$hColor, $hIcon] = $histColores[$h->decision] ?? ['var(--gray-400)', 'bi-dot'];
                                @endphp
                                <div style="position:relative;">
                                    <div style="position:absolute;left:-26px;top:2px;width:16px;height:16px;
                                                border-radius:50%;background:{{ $hColor }};display:flex;
                                                align-items:center;justify-content:center;">
                                        <i class="bi {{ $hIcon }}" style="font-size:8px;color:#fff;"></i>
                                    </div>
                                    <div style="font-size:12px;font-weight:700;color:{{ $hColor }};">
                                        {{ ucfirst($h->decision) }}
                                        @if($h->paso)
                                            <span style="font-weight:400;color:var(--gray-600);">
                                                — Paso {{ $h->paso->orden }}
                                            </span>
                                        @endif
                                    </div>
                                    <div style="font-size:11px;color:var(--gray-600);margin-top:2px;">
                                        <strong>{{ $h->usuario?->name ?? 'Sistema' }}</strong>
                                        <span style="color:var(--gray-400);margin-left:6px;">
                                            {{ $h->fecha?->format('d/m/Y H:i') ?? '—' }}
                                        </span>
                                    </div>
                                    @if($h->comentario)
                                        <div style="font-size:11px;background:#fff;
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
                    </div>
                </td>
            </tr>
            @endif

            @endforeach

            </tbody>
        </table>
    @endif
</div>

{{-- ── Modales de rechazo ───────────────────────────────────── --}}
@if($esResponsable)
    @foreach($todasEvidencias as $evidencia)
    @if(($evidencia->estado_actual ?? 1) === 2)
        @php
            $ejec = $evidencia->flujoEjecuciones->first();
        @endphp
        @if($ejec && is_null($ejec->finalizado_at))
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
                            <label class="gf-label" for="com{{ $evidencia->id_evidencia }}">
                                Motivo del rechazo <span style="color:var(--danger-text,#c0392b)">*</span>
                            </label>
                            <textarea id="com{{ $evidencia->id_evidencia }}"
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
    @endif
    @endforeach
@endif

@endsection
