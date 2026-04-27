@extends('administrator.app')

@section('title', 'Factores CNA')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-2">
    <div>
        <div class="gf-page-title">Factores</div>
        <div class="gf-page-sub">Lista de factores</div>
    </div>
    @can('create', App\Models\Factor::class)
        <a href="{{ route('factores.create') }}" class="gf-btn gf-btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo factor
        </a>
    @endcan
</div>

<div class="gf-card p-0" style="overflow:hidden;">
    <div class="gf-table-scroll">
    <table class="gf-table gf-table-compact">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Responsable</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($factores as $factor)
                <tr>
                    <td>{{ $factor->id_factor }}</td>
                    <td style="font-weight:500;">{{ $factor->name }}</td>
                    <td style="color:var(--gray-600);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $factor->description ?? '—' }}
                    </td>
                    <td>{{ $factor->responsableUser?->name ?? '—' }}</td>
                    <td>{{ $factor->fecha_inicio?->format('d/m/Y') }}</td>
                    <td>{{ $factor->fecha_fin?->format('d/m/Y') }}</td>
                    <td>
                        <span class="gf-status gf-status-{{ strtolower($factor->status?->name ?? 'borrador') }}">
                            {{ $factor->status?->name ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            {{-- Editar — solo Admin --}}
                            @can('update', $factor)
                                <a href="{{ route('factores.edit', $factor->id_factor) }}"
                                   class="gf-btn gf-btn-outline"
                                   style="height:30px;padding:0 10px;font-size:12px;"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endcan

                            {{-- Eliminar — solo Admin --}}
                            @can('delete', $factor)
                                <form action="{{ route('factores.destroy', $factor->id_factor) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Eliminar este factor?')">
                                    @csrf @method('DELETE')
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
                    <td colspan="8" style="text-align:center;padding:32px;color:var(--gray-400);">
                        <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                        No hay factores registrados.
                        @can('create', App\Models\Factor::class)
                            <a href="{{ route('factores.create') }}" style="color:var(--primary-mid);">Crear el primero</a>
                        @endcan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@endsection
