@extends('administrator.app')

@section('title', 'Resultados CNA')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-2">
    <div>
        <div class="gf-page-title">Resultados</div>
        <div class="gf-page-sub">Resultados asociados al proceso de acreditación CNA</div>
    </div>
    @can('create', App\Models\Resultado::class)
        <a href="{{ route('resultados.create') }}" class="gf-btn gf-btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo resultado
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
                <th>Tipo / Entidad</th>
                <th>Período</th>
                <th>Evidencias</th>
                <th style="text-align:center;">Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resultados as $r)
            @php
                $tipoLabel = ['factor' => 'Factor', 'caracteristica' => 'Característica', 'aspecto' => 'Aspecto'][$r->tipo_relacion] ?? $r->tipo_relacion;
                $tipoIcon  = ['factor' => 'bi-diagram-3', 'caracteristica' => 'bi-grid', 'aspecto' => 'bi-list-check'][$r->tipo_relacion] ?? 'bi-link';
            @endphp
                <tr>
                    <td style="color:var(--gray-400);font-size:12px;">{{ $r->id_resultado }}</td>
                    <td style="font-weight:500;max-width:220px;">
                        {{ $r->name }}
                        @if($r->description)
                            <div style="font-size:11px;color:var(--gray-500);font-weight:400;
                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;">
                                {{ $r->description }}
                            </div>
                        @endif
                    </td>
                    <td style="font-size:12px;">
                        <span style="display:inline-flex;align-items:center;gap:4px;
                                     background:var(--gray-50);border:1px solid var(--gray-100);
                                     border-radius:4px;padding:2px 7px;color:var(--gray-600);">
                            <i class="bi {{ $tipoIcon }}"></i>{{ $tipoLabel }}
                        </span>
                        <div style="font-size:11px;color:var(--gray-500);margin-top:2px;">
                            ID: {{ $r->id_referencia }}
                        </div>
                    </td>
                    <td style="font-size:12px;white-space:nowrap;">
                        {{ $r->fecha_inicio ? \Carbon\Carbon::parse($r->fecha_inicio)->format('d/m/Y') : '—' }}<br>
                        <span style="color:var(--gray-500);">{{ $r->fecha_fin ? \Carbon\Carbon::parse($r->fecha_fin)->format('d/m/Y') : '—' }}</span>
                    </td>
                    <td style="text-align:center;font-size:13px;">
                        <span style="background:var(--gray-100);border-radius:12px;
                                     padding:2px 10px;font-size:12px;color:var(--gray-700);">
                            {{ $r->evidencias_count ?? 0 }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        @if($r->status)
                            <span class="gf-status">{{ $r->status->name }}</span>
                        @else
                            <span style="color:var(--gray-400);">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            @can('update', $r)
                                <a href="{{ route('resultados.edit', $r->id_resultado) }}"
                                   class="gf-btn gf-btn-outline"
                                   style="height:30px;padding:0 10px;font-size:12px;"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endcan
                            @can('delete', $r)
                                <form action="{{ route('resultados.destroy', $r->id_resultado) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Eliminar el resultado «{{ addslashes($r->name) }}»?')">
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
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--gray-400);">
                        <i class="bi bi-bar-chart-line" style="font-size:28px;display:block;margin-bottom:10px;"></i>
                        No hay resultados registrados.
                        @can('create', App\Models\Resultado::class)
                            <a href="{{ route('resultados.create') }}"
                               style="color:var(--primary-mid);display:block;margin-top:6px;">
                                Registrar el primer resultado
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
