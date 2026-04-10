@extends('administrator.app')

@section('title', 'Aspectos por evaluar')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-2">
    <div>
        <div class="gf-page-title">Aspectos por evaluar</div>
        <div class="gf-page-sub">Unidades mínimas de evaluación del CNA</div>
    </div>
    <a href="{{ route('aspectos.create') }}" class="gf-btn gf-btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo aspecto
    </a>
</div>

<div class="gf-card p-0" style="overflow:hidden;">
    <table class="gf-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Característica</th>
                <th>Factor</th>
                <th>Responsable</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($aspectos as $a)
                <tr>
                    <td>{{ $a->id_aspecto }}</td>
                    <td style="font-weight:500;">{{ $a->name }}</td>
                    <td>
                        <span style="font-size:11px;background:var(--primary-light);color:var(--primary);
                                     padding:2px 8px;border-radius:8px;white-space:nowrap;">
                            {{ $a->caracteristica?->name ?? '—' }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:var(--gray-600);">
                        {{ $a->caracteristica?->factor?->name ?? '—' }}
                    </td>
                    <td>{{ $a->responsableUser?->name ?? '—' }}</td>
                    <td>{{ $a->fecha_inicio?->format('d/m/Y') }}</td>
                    <td>{{ $a->fecha_fin?->format('d/m/Y') }}</td>
                    <td>
                        <span class="gf-status gf-status-{{ strtolower(str_replace(' ', '-', $a->status?->name ?? 'borrador')) }}">
                            {{ $a->status?->name ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('aspectos.edit', $a->id_aspecto) }}"
                               class="gf-btn gf-btn-outline"
                               style="height:30px;padding:0 10px;font-size:12px;"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('aspectos.destroy', $a->id_aspecto) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar este aspecto?')">
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
                        No hay aspectos registrados.
                        <a href="{{ route('aspectos.create') }}"
                           style="color:var(--primary-mid);display:block;margin-top:6px;">
                            Crear el primer aspecto
                        </a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
