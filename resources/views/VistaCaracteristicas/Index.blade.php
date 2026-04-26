@extends('administrator.app')

@section('title', 'Características CNA')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-2">
    <div>
        <div class="gf-page-title">Características</div>
        <div class="gf-page-sub">Agrupación de aspectos por temática evaluada</div>
    </div>
    <a href="{{ route('caracteristicas.create') }}" class="gf-btn gf-btn-primary">
        <i class="bi bi-plus-lg"></i> Nueva característica
    </a>
</div>

<div class="gf-card p-0" style="overflow:hidden;">
    <div class="gf-table-scroll">
    <table class="gf-table gf-table-compact">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Factor</th>
                <th>Responsable</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Estado</th>
                <th style="text-align:center;">Aspectos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($caracteristicas as $c)
                <tr>
                    <td>{{ $c->id_caracteristica }}</td>
                    <td style="font-weight:500;">{{ $c->name }}</td>
                    <td>
                        <span style="font-size:11px;background:var(--primary-light);color:var(--primary);
                                     padding:2px 8px;border-radius:8px;white-space:nowrap;">
                            {{ $c->factor?->name ?? '—' }}
                        </span>
                    </td>
                    <td>{{ $c->responsableUser?->name ?? '—' }}</td>
                    <td>{{ $c->fecha_inicio?->format('d/m/Y') }}</td>
                    <td>{{ $c->fecha_fin?->format('d/m/Y') }}</td>
                    <td>
                        <span class="gf-status gf-status-{{ strtolower(str_replace(' ', '-', $c->status?->name ?? 'borrador')) }}">
                            {{ $c->status?->name ?? '—' }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span style="display:inline-block;min-width:24px;background:var(--primary);
                                     color:#fff;font-size:11px;font-weight:600;
                                     padding:2px 8px;border-radius:10px;text-align:center;">
                            {{ $c->aspectos->count() }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('caracteristicas.show', $c->id_caracteristica) }}"
                               class="gf-btn gf-btn-primary"
                               style="height:30px;padding:0 10px;font-size:12px;"
                               title="Ver evaluación">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('caracteristicas.edit', $c->id_caracteristica) }}"
                               class="gf-btn gf-btn-outline"
                               style="height:30px;padding:0 10px;font-size:12px;"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('caracteristicas.destroy', $c->id_caracteristica) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar esta característica? Se eliminarán también sus aspectos asociados.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="gf-btn gf-btn-danger"
                                        style="height:30px;padding:0 10px;font-size:12px;"
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:var(--gray-400);">
                        <i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:10px;"></i>
                        No hay características registradas.
                        <a href="{{ route('caracteristicas.create') }}"
                           style="color:var(--primary-mid);display:block;margin-top:6px;">
                            Crear la primera característica
                        </a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@endsection
