@extends('administrator.app')

@section('title', 'Auditoría')

@push('styles')
<style>
.aud-op-crear     { background:#d1fae5;color:#065f46;border:1px solid #6ee7b7; }
.aud-op-actualizar{ background:#dbeafe;color:#1e3a8a;border:1px solid #93c5fd; }
.aud-op-suprimir  { background:#fee2e2;color:#7f1d1d;border:1px solid #fca5a5; }
.aud-op-badge {
    display:inline-block;border-radius:4px;
    font-size:11px;font-weight:600;padding:2px 8px;text-transform:uppercase;letter-spacing:.3px;
}
.aud-val-box {
    font-size:11px;font-family:monospace;
    background:var(--gray-50);border:1px solid var(--gray-100);border-radius:4px;
    padding:4px 7px;max-width:200px;max-height:60px;overflow:auto;
    white-space:pre-wrap;word-break:break-all;color:var(--gray-600);
    display:block;
}
.aud-filter-form .gf-input,
.aud-filter-form .gf-select { height:36px;font-size:13px; }
.aud-filter-form .gf-btn   { height:36px;padding:0 14px;font-size:13px; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="d-flex align-items-start justify-content-between mb-3">
    <div>
        <div class="gf-page-title">Auditoría</div>
        <div class="gf-page-sub">Registro de todas las operaciones sobre el sistema — solo lectura</div>
    </div>
    <span style="background:#fef3c7;color:#92400e;border:1px solid #fbbf24;border-radius:6px;
                 padding:5px 12px;font-size:12px;font-weight:500;">
        <i class="bi bi-eye me-1"></i> Solo lectura
    </span>
</div>

{{-- ── Filtros ── --}}
<div class="gf-card mb-3">
    <form method="GET" action="{{ route('administrator.auditoria') }}" class="aud-filter-form">
        <div class="row g-2 align-items-end">

            <div class="col-md-2">
                <label class="gf-label" style="font-size:11px;">Objeto</label>
                <select name="objeto" class="gf-select">
                    <option value="">— Todos —</option>
                    @foreach($objetos as $obj)
                        <option value="{{ $obj }}" {{ request('objeto') === $obj ? 'selected' : '' }}>
                            {{ $obj }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="gf-label" style="font-size:11px;">Operación</label>
                <select name="operacion" class="gf-select">
                    <option value="">— Todas —</option>
                    <option value="crear"      {{ request('operacion') === 'crear'      ? 'selected' : '' }}>Crear</option>
                    <option value="actualizar" {{ request('operacion') === 'actualizar' ? 'selected' : '' }}>Actualizar</option>
                    <option value="suprimir"   {{ request('operacion') === 'suprimir'   ? 'selected' : '' }}>Suprimir</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="gf-label" style="font-size:11px;">Usuario</label>
                <select name="usuario_id" class="gf-select">
                    <option value="">— Todos —</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} {{ $u->first_surname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1">
                <label class="gf-label" style="font-size:11px;">ID registro</label>
                <input type="text" name="registro" class="gf-input"
                       value="{{ request('registro') }}" placeholder="Ej. 5">
            </div>

            <div class="col-md-2">
                <label class="gf-label" style="font-size:11px;">Desde</label>
                <input type="date" name="fecha_desde" class="gf-input"
                       value="{{ request('fecha_desde') }}">
            </div>

            <div class="col-md-2">
                <label class="gf-label" style="font-size:11px;">Hasta</label>
                <input type="date" name="fecha_hasta" class="gf-input"
                       value="{{ request('fecha_hasta') }}">
            </div>

            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="gf-btn gf-btn-primary" title="Filtrar">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('administrator.auditoria') }}" class="gf-btn gf-btn-outline" title="Limpiar">
                    <i class="bi bi-x"></i>
                </a>
            </div>

        </div>
    </form>
</div>

{{-- Contador --}}
<div style="font-size:12px;color:var(--gray-400);margin-bottom:8px;">
    {{ number_format($registros->total()) }} registros encontrados
    @if(request()->hasAny(['objeto','operacion','usuario_id','registro','fecha_desde','fecha_hasta']))
        — <a href="{{ route('administrator.auditoria') }}" style="color:var(--primary-mid);">limpiar filtros</a>
    @endif
</div>

{{-- ── Tabla ── --}}
<div class="gf-card p-0" style="overflow:hidden;">
    <table class="gf-table">
        <thead>
            <tr>
                <th style="white-space:nowrap;">Fecha</th>
                <th>Objeto</th>
                <th style="text-align:center;">ID</th>
                <th>Atributo</th>
                <th style="text-align:center;">Operación</th>
                <th>Valor anterior</th>
                <th>Valor nuevo</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registros as $r)
            <tr>
                {{-- Fecha --}}
                <td style="white-space:nowrap;font-size:12px;">
                    {{ $r->fecha_modificacion?->format('d/m/Y') }}
                    <div style="color:var(--gray-400);">{{ $r->fecha_modificacion?->format('H:i:s') }}</div>
                </td>

                {{-- Objeto --}}
                <td style="font-weight:500;font-size:13px;">{{ $r->objeto }}</td>

                {{-- ID registro --}}
                <td style="text-align:center;font-size:12px;color:var(--gray-400);">{{ $r->registro }}</td>

                {{-- Atributo --}}
                <td style="font-size:12px;">
                    @if($r->atributo)
                        <code style="background:var(--gray-50);border:1px solid var(--gray-100);
                                     border-radius:3px;padding:1px 5px;font-size:11px;">
                            {{ $r->atributo }}
                        </code>
                    @else
                        <span style="color:var(--gray-400);">—</span>
                    @endif
                </td>

                {{-- Operación --}}
                <td style="text-align:center;">
                    <span class="aud-op-badge aud-op-{{ $r->operacion }}">
                        @switch($r->operacion)
                            @case('crear')      <i class="bi bi-plus-lg"></i> Crear      @break
                            @case('actualizar') <i class="bi bi-pencil"></i> Actualizar @break
                            @case('suprimir')   <i class="bi bi-trash"></i> Suprimir   @break
                            @default            {{ $r->operacion }}
                        @endswitch
                    </span>
                </td>

                {{-- Valor anterior --}}
                <td>
                    @if(!is_null($r->valor_antiguo))
                        @php $decoded = json_decode($r->valor_antiguo, true); @endphp
                        @if($decoded)
                            <span class="aud-val-box" title="{{ $r->valor_antiguo }}">
                                {!! collect($decoded)->map(fn($v,$k) => "<b>{$k}</b>: " . (is_null($v) ? 'null' : e($v)))->implode('<br>') !!}
                            </span>
                        @else
                            <span class="aud-val-box">{{ $r->valor_antiguo }}</span>
                        @endif
                    @else
                        <span style="color:var(--gray-400);font-size:12px;">—</span>
                    @endif
                </td>

                {{-- Valor nuevo --}}
                <td>
                    @if(!is_null($r->valor_nuevo))
                        @php $decoded = json_decode($r->valor_nuevo, true); @endphp
                        @if($decoded)
                            <span class="aud-val-box" title="{{ $r->valor_nuevo }}">
                                {!! collect($decoded)->map(fn($v,$k) => "<b>{$k}</b>: " . (is_null($v) ? 'null' : e($v)))->implode('<br>') !!}
                            </span>
                        @else
                            <span class="aud-val-box">{{ $r->valor_nuevo }}</span>
                        @endif
                    @else
                        <span style="color:var(--gray-400);font-size:12px;">—</span>
                    @endif
                </td>

                {{-- Usuario --}}
                <td style="font-size:12px;white-space:nowrap;">
                    @if($r->usuario)
                        <div style="display:flex;align-items:center;gap:6px;">
                            <div style="width:24px;height:24px;border-radius:50%;background:var(--primary-light);
                                        color:var(--primary);display:flex;align-items:center;justify-content:center;
                                        font-size:10px;font-weight:600;flex-shrink:0;">
                                {{ strtoupper(substr($r->usuario->name,0,1)) }}
                            </div>
                            <span>{{ $r->usuario->name }}</span>
                        </div>
                    @else
                        <span style="color:var(--gray-400);">Sistema</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:48px;color:var(--gray-400);">
                    <i class="bi bi-journal-x" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                    No hay registros de auditoría
                    @if(request()->hasAny(['objeto','operacion','usuario_id','registro','fecha_desde','fecha_hasta']))
                        con los filtros actuales.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginación --}}
@if($registros->hasPages())
<div class="d-flex justify-content-center mt-3">
    {{ $registros->links() }}
</div>
@endif

@endsection
