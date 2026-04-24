@extends('administrator.app')

@section('title', 'Evaluación: ' . $caracteristica->name)

@section('content')

@php
    $estadoClases = [1 => 'borrador', 2 => 'revision', 3 => 'aprobado', 4 => 'rechazado'];
    $estadoIconos = [
        1 => 'bi-file-earmark',
        2 => 'bi-hourglass-split',
        3 => 'bi-check-circle-fill',
        4 => 'bi-x-circle-fill',
    ];
    $rolUsuario = auth()->user()->id_rol;
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

{{-- Encabezado de la característica --}}
<div class="gf-card mb-4" style="padding:20px 24px;">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="gf-page-title" style="margin-bottom:0;">{{ $caracteristica->name }}</div>
                <span class="gf-status gf-status-{{ strtolower($caracteristica->status?->name ?? 'activo') }}">
                    {{ $caracteristica->status?->name ?? '—' }}
                </span>
            </div>
            <div class="gf-page-sub" style="margin-bottom:8px;">
                Factor: <strong>{{ $caracteristica->factor?->name ?? '—' }}</strong>
                &nbsp;·&nbsp;
                Responsable: <strong>{{ $caracteristica->responsableUser?->name ?? '—' }}</strong>
                &nbsp;·&nbsp;
                {{ $caracteristica->fecha_inicio?->format('d/m/Y') }} – {{ $caracteristica->fecha_fin?->format('d/m/Y') }}
            </div>
        </div>
        <a href="{{ route('caracteristicas.index') }}" class="gf-btn gf-btn-outline" style="height:34px;">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

{{-- Aspectos con sus evidencias --}}
@forelse($caracteristica->aspectos as $aspecto)
    <div class="gf-card mb-3 p-0" style="overflow:hidden;">

        {{-- Cabecera del aspecto --}}
        <div style="background:var(--primary);padding:12px 20px;display:flex;
                    align-items:center;justify-content:space-between;">
            <div>
                <span style="color:#fff;font-weight:600;font-size:14px;">
                    <i class="bi bi-layers me-2"></i>{{ $aspecto->name }}
                </span>
                @if($aspecto->flujoActivo)
                    <span style="font-size:11px;color:rgba(255,255,255,0.75);margin-left:10px;">
                        <i class="bi bi-diagram-3"></i> Flujo activo: {{ $aspecto->flujoActivo->nombre }}
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

        {{-- Tabla de evidencias del aspecto --}}
        @if($aspecto->evidencias->isEmpty())
            <div style="padding:28px;text-align:center;color:var(--gray-400);font-size:13px;">
                <i class="bi bi-folder2-open" style="font-size:22px;display:block;margin-bottom:6px;"></i>
                No hay evidencias registradas para este aspecto.
                <a href="{{ route('evidencias.create') }}" style="color:var(--primary-mid);display:block;margin-top:4px;font-size:12px;">
                    Registrar evidencia
                </a>
            </div>
        @else
            <table class="gf-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Período</th>
                        <th style="text-align:center;">Estado documento</th>
                        <th>Paso actual</th>
                        <th>Registrado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aspecto->evidencias as $evidencia)
                    @php
                        $ejecucion     = $evidencia->flujoEjecuciones->first();
                        $estado        = $evidencia->estado_actual ?? 1;
                        $claseEstado   = $estadoClases[$estado] ?? 'borrador';
                        $iconoEstado   = $estadoIconos[$estado] ?? 'bi-file-earmark';
                        $puedeAprobar  = $ejecucion
                                         && $ejecucion->pasoActual
                                         && $ejecucion->pasoActual->rol_requerido == $rolUsuario;
                    @endphp
                    <tr>
                        {{-- Nombre --}}
                        <td style="font-weight:500;max-width:240px;">
                            {{ $evidencia->nombre }}
                        </td>

                        {{-- Período --}}
                        <td style="font-size:12px;white-space:nowrap;">
                            {{ $evidencia->fecha_inicio?->format('d/m/Y') }}<br>
                            <span style="color:var(--gray-500);">{{ $evidencia->fecha_fin?->format('d/m/Y') }}</span>
                        </td>

                        {{-- Estado documento --}}
                        <td style="text-align:center;">
                            <span class="gf-status gf-status-{{ $claseEstado }}">
                                <i class="bi {{ $iconoEstado }} me-1"></i>
                                {{ $evidencia->estadoActual?->name ?? 'Borrador' }}
                            </span>
                        </td>

                        {{-- Paso actual --}}
                        <td style="font-size:12px;">
                            @if($ejecucion && $ejecucion->pasoActual)
                                <span style="color:var(--primary);font-weight:500;">
                                    Paso {{ $ejecucion->pasoActual->orden }}
                                </span>
                            @elseif($estado == 3)
                                <span style="color:var(--success-text,#1a6b3a);">
                                    <i class="bi bi-check2-all"></i> Completado
                                </span>
                            @else
                                <span style="color:var(--gray-400);">—</span>
                            @endif
                        </td>

                        {{-- Registrado por --}}
                        <td style="font-size:12px;color:var(--gray-600);">
                            {{ $evidencia->createdBy?->name ?? '—' }}
                        </td>

                        {{-- Acciones según estado y rol --}}
                        <td>
                            <div class="d-flex gap-2 flex-wrap">

                                {{-- Borrador → Enviar a revisión --}}
                                @if($estado == 1)
                                    <form action="{{ route('flujo.iniciar', $evidencia->id_evidencia) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Enviar esta evidencia al flujo de aprobación?')">
                                        @csrf
                                        <button type="submit" class="gf-btn gf-btn-primary"
                                                style="height:30px;padding:0 12px;font-size:12px;">
                                            <i class="bi bi-send"></i> Enviar a revisión
                                        </button>
                                    </form>
                                @endif

                                {{-- En revisión + rol correcto → Aprobar / Rechazar --}}
                                @if($estado == 2 && $puedeAprobar)
                                    <form action="{{ route('flujo.decision', $evidencia->id_evidencia) }}"
                                          method="POST">
                                        @csrf
                                        <input type="hidden" name="decision" value="aprobado">
                                        <button type="submit" class="gf-btn"
                                                style="height:30px;padding:0 12px;font-size:12px;
                                                       background:#1a6b3a;color:#fff;border:none;"
                                                onclick="return confirm('¿Aprobar esta evidencia?')">
                                            <i class="bi bi-check-lg"></i> Aprobar
                                        </button>
                                    </form>

                                    <button type="button"
                                            class="gf-btn gf-btn-danger"
                                            style="height:30px;padding:0 12px;font-size:12px;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalRechazar{{ $evidencia->id_evidencia }}">
                                        <i class="bi bi-x-lg"></i> Rechazar
                                    </button>
                                @endif

                                {{-- Rechazado → Reiniciar (solo quien la creó) --}}
                                @if($estado == 4 && auth()->id() == $evidencia->created_by)
                                    <form action="{{ route('flujo.reiniciar', $evidencia->id_evidencia) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Reiniciar el flujo? La evidencia volverá a revisión.')">
                                        @csrf
                                        <button type="submit" class="gf-btn gf-btn-outline"
                                                style="height:30px;padding:0 12px;font-size:12px;">
                                            <i class="bi bi-arrow-counterclockwise"></i> Reiniciar
                                        </button>
                                    </form>
                                @endif

                                {{-- Siempre disponible: editar evidencia --}}
                                @if(in_array($estado, [1, 4]))
                                    <a href="{{ route('evidencias.edit', $evidencia->id_evidencia) }}"
                                       class="gf-btn gf-btn-outline"
                                       style="height:30px;padding:0 10px;font-size:12px;"
                                       title="Editar evidencia">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Modales de rechazo para cada evidencia del aspecto --}}
    @foreach($aspecto->evidencias as $evidencia)
        @if(($evidencia->estado_actual ?? 1) == 2)
        @php
            $ejec        = $evidencia->flujoEjecuciones->first();
            $puedeModal  = $ejec && $ejec->pasoActual && $ejec->pasoActual->rol_requerido == $rolUsuario;
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
                                Motivo del rechazo <span style="color:var(--danger-text)">*</span>
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
        @endif
    @endforeach

@empty
    <div class="gf-card" style="text-align:center;padding:48px;color:var(--gray-400);">
        <i class="bi bi-layers" style="font-size:32px;display:block;margin-bottom:12px;"></i>
        Esta característica no tiene aspectos registrados.
    </div>
@endforelse

@endsection
