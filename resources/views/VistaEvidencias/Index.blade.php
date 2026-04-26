@extends('administrator.app')

@section('title', 'Evidencias CNA')

@section('content')

@php
    $estadoClases = [1 => 'borrador', 2 => 'revision', 3 => 'aprobado', 4 => 'rechazado'];
    $estadoLabels = [1 => 'Borrador', 2 => 'En revisión', 3 => 'Aprobado', 4 => 'Rechazado'];
    $estadoIconos = [1 => 'bi-file-earmark', 2 => 'bi-hourglass-split', 3 => 'bi-check-circle-fill', 4 => 'bi-x-circle-fill'];
@endphp

<div class="d-flex align-items-center justify-content-between mb-2">
    <div>
        <div class="gf-page-title">Evidencias</div>
        <div class="gf-page-sub">Documentos de evidencia del proceso de acreditación CNA</div>
    </div>
    @can('create', App\Models\Evidencia::class)
        <a href="{{ route('evidencias.create') }}" class="gf-btn gf-btn-primary">
            <i class="bi bi-plus-lg"></i> Nueva evidencia
        </a>
    @endcan
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert"
         style="border-radius:8px;font-size:14px;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="gf-card p-0" style="overflow:hidden;">
    <div class="gf-table-scroll">
    <table class="gf-table gf-table-compact">
        <thead>
            <tr>
                <th style="width:40px;">#</th>
                <th style="width:25%;">Nombre</th>
                <th style="width:22%;">Aspecto / Característica</th>
                <th style="width:110px;white-space:nowrap;">Período</th>
                <th style="width:120px;text-align:center;white-space:nowrap;">Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($evidencias as $e)
            @php
                $estadoEv      = $e->estado_actual ?? 1;
                $claseEv       = $estadoClases[$estadoEv] ?? 'borrador';
                $labelEv       = $estadoLabels[$estadoEv] ?? 'Borrador';
                $iconoEv       = $estadoIconos[$estadoEv] ?? 'bi-file-earmark';
                $esMiEvidencia = auth()->id() == $e->created_by;
            @endphp
                <tr>
                    {{-- # --}}
                    <td style="color:var(--gray-400);">{{ $e->id_evidencia }}</td>

                    {{-- Nombre --}}
                    <td style="max-width:0;">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
                                    font-weight:500;" title="{{ $e->nombre }}">
                            {{ $e->nombre }}
                        </div>
                        @if($e->descripcion)
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
                                        font-size:11px;color:var(--gray-400);" title="{{ $e->descripcion }}">
                                {{ $e->descripcion }}
                            </div>
                        @endif
                    </td>

                    {{-- Aspecto / Característica --}}
                    <td style="max-width:0;">
                        @if($e->aspecto)
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
                                        font-size:11px;color:var(--gray-400);"
                                 title="{{ $e->aspecto->caracteristica?->name ?? '' }}">
                                {{ $e->aspecto->caracteristica?->name ?? '—' }}
                            </div>
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
                                        font-weight:500;"
                                 title="{{ $e->aspecto->name }}">
                                {{ $e->aspecto->name }}
                            </div>
                        @else
                            <span style="color:var(--gray-400);">Sin aspecto</span>
                        @endif
                    </td>

                    {{-- Período --}}
                    <td style="white-space:nowrap;">
                        {{ $e->fecha_inicio?->format('d/m/Y') ?? '—' }}<br>
                        <span style="color:var(--gray-400);">{{ $e->fecha_fin?->format('d/m/Y') ?? '—' }}</span>
                    </td>

                    {{-- Estado --}}
                    <td style="text-align:center;white-space:nowrap;">
                        <span class="gf-status gf-status-{{ $claseEv }}">
                            <i class="bi {{ $iconoEv }} me-1"></i>{{ $labelEv }}
                        </span>
                    </td>

                    {{-- Acciones --}}
                    <td style="white-space:nowrap;">
                        <div class="d-flex gap-1">

                            {{-- Enviar a revisión (Borrador + creador) --}}
                            @if($estadoEv == 1 && $esMiEvidencia)
                                <form action="{{ route('flujo.iniciar', $e->id_evidencia) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Enviar esta evidencia al flujo de aprobación?')">
                                    @csrf
                                    <button type="submit" class="gf-btn gf-btn-primary"
                                            style="height:30px;padding:0 10px;font-size:12px;"
                                            title="Enviar a revisión">
                                        <i class="bi bi-send"></i> Enviar
                                    </button>
                                </form>
                            @endif

                            {{-- Reenviar (Rechazado + creador) --}}
                            @if($estadoEv == 4 && $esMiEvidencia)
                                <form action="{{ route('flujo.reiniciar', $e->id_evidencia) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Volver a enviar esta evidencia al flujo de aprobación?')">
                                    @csrf
                                    <button type="submit" class="gf-btn gf-btn-primary"
                                            style="height:30px;padding:0 10px;font-size:12px;"
                                            title="Volver a enviar">
                                        <i class="bi bi-arrow-clockwise"></i> Reenviar
                                    </button>
                                </form>
                            @endif

                            {{-- Editar (Borrador o Rechazado + creador) --}}
                            @if(in_array($estadoEv, [1, 4]) && $esMiEvidencia)
                                <a href="{{ route('evidencias.edit', $e->id_evidencia) }}"
                                   class="gf-btn gf-btn-outline"
                                   style="height:30px;padding:0 10px;font-size:12px;"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            {{-- Eliminar --}}
                            @can('delete', $e)
                                <form action="{{ route('evidencias.destroy', $e->id_evidencia) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Eliminar esta evidencia?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="gf-btn gf-btn-danger"
                                            style="height:30px;padding:0 10px;font-size:12px;"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endcan

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:var(--gray-400);">
                        <i class="bi bi-folder2-open" style="font-size:28px;display:block;margin-bottom:10px;"></i>
                        No hay evidencias registradas.
                        @can('create', App\Models\Evidencia::class)
                            <a href="{{ route('evidencias.create') }}"
                               style="color:var(--primary-mid);display:block;margin-top:6px;">
                                Registrar la primera evidencia
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@endsection
