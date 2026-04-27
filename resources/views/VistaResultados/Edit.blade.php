@extends('administrator.app')

@section('title', 'Editar resultado')

@section('content')

<div class="gf-breadcrumb">
    <a href="{{ route('resultados.index') }}">Resultados</a>
    <span class="gf-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:10px;"></i></span>
    <span>Editar: {{ $resultado->name }}</span>
</div>

<div class="gf-page-title">Editar resultado</div>
<div class="gf-page-sub">Modifica los datos del resultado y sus evidencias asociadas.</div>

<div class="gf-card" style="max-width:760px;">

    <form method="POST" action="{{ route('resultados.update', $resultado->id_resultado) }}">
        @csrf
        @method('PUT')
        {{-- Marker para que el servicio siempre sincronice evidencias al guardar --}}
        <input type="hidden" name="evidencias_enviadas" value="1">

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="gf-label" for="name">
                Nombre <span style="color:var(--danger-text)">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   class="gf-input @error('name') is-invalid @enderror"
                   value="{{ old('name', $resultado->name) }}"
                   required>
            @error('name')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label class="gf-label" for="description">Descripción</label>
            <textarea id="description"
                      name="description"
                      rows="3"
                      class="gf-textarea @error('description') is-invalid @enderror">{{ old('description', $resultado->description) }}</textarea>
            @error('description')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Fechas --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="gf-label" for="fecha_inicio">
                    Fecha inicio <span style="color:var(--danger-text)">*</span>
                </label>
                <input type="date"
                       id="fecha_inicio"
                       name="fecha_inicio"
                       class="gf-input @error('fecha_inicio') is-invalid @enderror"
                       value="{{ old('fecha_inicio', $resultado->fecha_inicio ? \Carbon\Carbon::parse($resultado->fecha_inicio)->format('Y-m-d') : '') }}"
                       required>
                @error('fecha_inicio')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="gf-label" for="fecha_fin">
                    Fecha fin <span style="color:var(--danger-text)">*</span>
                </label>
                <input type="date"
                       id="fecha_fin"
                       name="fecha_fin"
                       class="gf-input @error('fecha_fin') is-invalid @enderror"
                       value="{{ old('fecha_fin', $resultado->fecha_fin ? \Carbon\Carbon::parse($resultado->fecha_fin)->format('Y-m-d') : '') }}"
                       required>
                @error('fecha_fin')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Estado CNA --}}
        <div class="mb-4">
            <label class="gf-label" for="status_id">
                Estado CNA <span style="color:var(--danger-text)">*</span>
            </label>
            <select id="status_id"
                    name="status_id"
                    class="gf-select @error('status_id') is-invalid @enderror"
                    required>
                <option value="">— Seleccionar —</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id_status }}"
                        {{ old('status_id', $resultado->status_id) == $status->id_status ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
            @error('status_id')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Evidencias asociadas --}}
        <div class="mb-4">
            <label class="gf-label">Evidencias asociadas</label>
            <div style="border:1px solid var(--gray-200);border-radius:8px;
                        max-height:380px;overflow-y:auto;">
                @php
                    $seleccionadas = old('evidencias',
                        $resultado->evidencias->pluck('id_evidencia')->toArray()
                    );
                @endphp
                @forelse($factores as $factor)
                    @php $carConEv = $factor->caracteristicas->filter(fn($c) => $c->aspectos->some(fn($a) => $a->evidencias->isNotEmpty())); @endphp
                    @if($carConEv->isNotEmpty())
                    {{-- Factor --}}
                    <div style="padding:7px 12px 5px;font-weight:600;font-size:13px;
                                color:var(--primary);background:var(--gray-50);
                                border-bottom:1px solid var(--gray-100);position:sticky;top:0;z-index:1;">
                        <i class="bi bi-stack me-1" style="font-size:11px;"></i>{{ $factor->name }}
                    </div>
                    @foreach($carConEv as $car)
                        @php $aspConEv = $car->aspectos->filter(fn($a) => $a->evidencias->isNotEmpty()); @endphp
                        {{-- Característica --}}
                        <div style="padding:5px 20px 2px;font-size:12px;font-weight:600;
                                    color:var(--gray-600);background:var(--gray-50);
                                    border-bottom:1px solid var(--gray-100);">
                            {{ $car->name }}
                        </div>
                        @foreach($aspConEv as $asp)
                        {{-- Aspecto --}}
                        <div style="padding:4px 28px 2px;font-size:11px;font-weight:500;
                                    color:var(--gray-400);border-bottom:1px solid var(--gray-50);">
                            <i class="bi bi-bookmark me-1"></i>{{ $asp->name }}
                        </div>
                        @foreach($asp->evidencias as $ev)
                        @php
                            $stClase = [1=>'borrador',2=>'revision',3=>'aprobado',4=>'rechazado'][$ev->estado_actual] ?? 'borrador';
                            $stLabel = [1=>'Borrador',2=>'En revisión',3=>'Aprobado',4=>'Rechazado'][$ev->estado_actual] ?? '';
                        @endphp
                        <label for="ev_{{ $ev->id_evidencia }}"
                               style="display:flex;align-items:center;gap:8px;
                                      padding:6px 36px;cursor:pointer;
                                      border-bottom:1px solid var(--gray-50);
                                      font-size:12px;font-weight:normal;
                                      transition:background .12s;"
                               onmouseover="this.style.background='var(--gray-50)'"
                               onmouseout="this.style.background=''">
                            <input type="checkbox"
                                   id="ev_{{ $ev->id_evidencia }}"
                                   name="evidencias[]"
                                   value="{{ $ev->id_evidencia }}"
                                   {{ in_array($ev->id_evidencia, $seleccionadas) ? 'checked' : '' }}>
                            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                  title="{{ $ev->nombre }}">{{ $ev->nombre }}</span>
                            <span class="gf-status gf-status-{{ $stClase }}"
                                  style="font-size:10px;padding:1px 6px;flex-shrink:0;">
                                {{ $stLabel }}
                            </span>
                        </label>
                        @endforeach
                        @endforeach
                    @endforeach
                    @endif
                @empty
                    <div style="padding:24px;text-align:center;color:var(--gray-400);font-size:13px;">
                        No hay evidencias registradas.
                    </div>
                @endforelse
            </div>
            @error('evidencias')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Info auditoría --}}
        <div style="background:var(--gray-50);border:1px solid var(--gray-100);border-radius:8px;
                    padding:12px 16px;margin-bottom:20px;font-size:12px;color:var(--gray-600);">
            <div style="margin-bottom:4px;">
                <i class="bi bi-clock-history" style="color:var(--primary);"></i>
                Creado por: <strong>{{ $resultado->createdBy?->name ?? 'Sistema' }}</strong>
                — {{ $resultado->created_at?->format('d/m/Y H:i') }}
            </div>
            <div>
                <i class="bi bi-pencil-square" style="color:var(--primary);"></i>
                Última actualización: <strong>{{ $resultado->updatedBy?->name ?? 'Sistema' }}</strong>
                — {{ $resultado->updated_at?->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="gf-btn gf-btn-primary">
                <i class="bi bi-check-lg"></i> Actualizar resultado
            </button>
            <a href="{{ route('resultados.index') }}" class="gf-btn gf-btn-outline">
                <i class="bi bi-x"></i> Cancelar
            </a>
        </div>

    </form>
</div>

@endsection
