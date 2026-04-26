@extends('administrator.app')

@section('title', 'Evidencias CNA')

@section('content')

@php
    $estadoClases = [1 => 'borrador', 2 => 'revision', 3 => 'aprobado', 4 => 'rechazado'];
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
    <table class="gf-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Aspecto / Característica</th>
                <th>Período</th>
                <th style="text-align:center;">Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($evidencias as $e)
            @php
                $estadoEv  = $e->estado_actual ?? 1;
                $claseEv   = $estadoClases[$estadoEv] ?? 'borrador';
                $labelEv   = [1=>'Borrador',2=>'En revisión',3=>'Aprobado',4=>'Rechazado'][$estadoEv] ?? 'Borrador';
                $iconoEv   = [1=>'bi-file-earmark',2=>'bi-hourglass-split',3=>'bi-check-circle-fill',4=>'bi-x-circle-fill'][$estadoEv] ?? 'bi-file-earmark';
                $esMiEvidencia = auth()->id() == $e->created_by;
            @endphp
                <tr>
                    <td>{{ $e->id_evidencia }}</td>
                    <td style="font-weight:500;max-width:200px;">
                        {{ $e->nombre }}
                        @if($e->descripcion)
                            <div style="font-size:11px;color:var(--gray-500);font-weight:400;
                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;">
                                {{ $e->descripcion }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:12px;line-height:1.4;">
                            @if($e->aspecto)
                                <span style="color:var(--gray-500);font-size:11px;">
                                    {{ $e->aspecto->caracteristica?->name ?? '—' }}
                                </span><br>
                                <strong>{{ $e->aspecto->name }}</strong>
                            @else
                                <span style="color:var(--gray-400);">Sin aspecto</span>
                            @endif
                        </div>
                    </td>
                    <td style="font-size:12px;white-space:nowrap;">
                        {{ $e->fecha_inicio?->format('d/m/Y') ?? '—' }}<br>
                        <span style="color:var(--gray-500);">{{ $e->fecha_fin?->format('d/m/Y') ?? '—' }}</span>
                    </td>
                    <td style="text-align:center;">
                        <span class="gf-status gf-status-{{ $claseEv }}">
                            <i class="bi {{ $iconoEv }} me-1"></i>{{ $labelEv }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">

                            {{-- Enviar a revisión (solo Borrador + creador) --}}
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

@endsection
