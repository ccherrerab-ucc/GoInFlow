@extends('administrator.app')

@section('title', 'Editar característica')

@section('content')

<div class="gf-breadcrumb">
    <a href="{{ route('caracteristicas.index') }}">Características</a>
    <span class="gf-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:10px;"></i></span>
    <span>Editar: {{ $caracteristica->name }}</span>
</div>

<div class="gf-page-title">Editar característica</div>
<div class="gf-page-sub">Modifica los datos de la característica CNA.</div>

<div class="gf-card" style="max-width:760px;">

    <form method="POST" action="{{ route('caracteristicas.update', $caracteristica->id_caracteristica) }}">
        @csrf
        @method('PUT')

        {{-- Factor --}}
        <div class="mb-3">
            <label class="gf-label" for="factor_id">
                Factor <span style="color:var(--danger-text)">*</span>
            </label>
            <select id="factor_id"
                    name="factor_id"
                    class="gf-select @error('factor_id') is-invalid @enderror"
                    required>
                <option value="">— Seleccionar factor —</option>
                @foreach($factores as $factor)
                    <option value="{{ $factor->id_factor }}"
                        {{ old('factor_id', $caracteristica->factor_id) == $factor->id_factor ? 'selected' : '' }}>
                        {{ $factor->name }}
                    </option>
                @endforeach
            </select>
            @error('factor_id')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="gf-label" for="name">
                Nombre <span style="color:var(--danger-text)">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   class="gf-input @error('name') is-invalid @enderror"
                   value="{{ old('name', $caracteristica->name) }}"
                   placeholder="Ej. Característica 1 — Misión institucional"
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
                      class="gf-textarea @error('description') is-invalid @enderror"
                      placeholder="Descripción de la característica...">{{ old('description', $caracteristica->description) }}</textarea>
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
                <input type="datetime-local"
                       id="fecha_inicio"
                       name="fecha_inicio"
                       class="gf-input @error('fecha_inicio') is-invalid @enderror"
                       value="{{ old('fecha_inicio', $caracteristica->fecha_inicio?->format('Y-m-d\TH:i')) }}"
                       required>
                @error('fecha_inicio')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="gf-label" for="fecha_fin">
                    Fecha fin <span style="color:var(--danger-text)">*</span>
                </label>
                <input type="datetime-local"
                       id="fecha_fin"
                       name="fecha_fin"
                       class="gf-input @error('fecha_fin') is-invalid @enderror"
                       value="{{ old('fecha_fin', $caracteristica->fecha_fin?->format('Y-m-d\TH:i')) }}"
                       required>
                @error('fecha_fin')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Responsable y Estado --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="gf-label" for="responsable">Responsable</label>
                <select id="responsable"
                        name="responsable"
                        class="gf-select @error('responsable') is-invalid @enderror">
                    <option value="">— Sin asignar —</option>
                    @foreach($responsables as $user)
                        <option value="{{ $user->id }}"
                            {{ old('responsable', $caracteristica->responsable) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} {{ $user->first_surname }}
                        </option>
                    @endforeach
                </select>
                @error('responsable')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="gf-label" for="status_id">
                    Estado <span style="color:var(--danger-text)">*</span>
                </label>
                <select id="status_id"
                        name="status_id"
                        class="gf-select @error('status_id') is-invalid @enderror"
                        required>
                    <option value="">— Seleccionar —</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id_status }}"
                            {{ old('status_id', $caracteristica->status_id) == $status->id_status ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
                @error('status_id')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Info auditoría --}}
        <div style="background:var(--gray-50);border:1px solid var(--gray-100);border-radius:8px;
                    padding:12px 16px;margin-bottom:20px;font-size:12px;color:var(--gray-600);">
            <div style="margin-bottom:4px;">
                <i class="bi bi-clock-history" style="color:var(--primary);"></i>
                Creado por: <strong>{{ $caracteristica->creador?->name ?? 'Sistema' }}</strong>
                — {{ $caracteristica->created_at?->format('d/m/Y H:i') }}
            </div>
            <div>
                <i class="bi bi-pencil-square" style="color:var(--primary);"></i>
                Última actualización: <strong>{{ $caracteristica->actualizador?->name ?? 'Sistema' }}</strong>
                — {{ $caracteristica->updated_at?->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="gf-btn gf-btn-primary">
                <i class="bi bi-check-lg"></i> Actualizar característica
            </button>
            <a href="{{ route('caracteristicas.index') }}" class="gf-btn gf-btn-outline">
                <i class="bi bi-x"></i> Cancelar
            </a>
        </div>

    </form>
</div>


@endsection
