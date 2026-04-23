@extends('administrator.app')

@section('title', 'Nueva Evidencia')

@section('content')

<div class="gf-breadcrumb">
    <a href="{{ route('evidencias.index') }}">Evidencias</a>
    <span class="gf-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:10px;"></i></span>
    <span>Nueva evidencia</span>
</div>

<div class="gf-page-title">Nueva evidencia</div>
<div class="gf-page-sub">Asocia la evidencia al Aspecto correspondiente del CNA.</div>

<div class="gf-card" style="max-width:760px;">

    <form method="POST" action="{{ route('evidencias.store') }}">
        @csrf

        {{-- Aspecto --}}
        <div class="mb-3">
            <label class="gf-label" for="aspecto_id">
                Aspecto <span style="color:var(--danger-text)">*</span>
            </label>
            <select id="aspecto_id"
                    name="aspecto_id"
                    class="gf-select @error('aspecto_id') is-invalid @enderror"
                    required>
                <option value="">— Seleccionar aspecto —</option>
                @foreach($aspectos as $aspecto)
                    <option value="{{ $aspecto->id_aspecto }}"
                        {{ old('aspecto_id') == $aspecto->id_aspecto ? 'selected' : '' }}>
                        {{ $aspecto->name }}
                    </option>
                @endforeach
            </select>
            @error('aspecto_id')
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
                   value="{{ old('name') }}"
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
                      placeholder="Descripción de la característica...">{{ old('description') }}</textarea>
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
                       value="{{ old('fecha_inicio') }}"
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
                       value="{{ old('fecha_fin') }}"
                       required>
                @error('fecha_fin')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Responsable y Estado --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="gf-label" for="responsable">Responsable</label>
                <select id="responsable"
                        name="responsable"
                        class="gf-select @error('responsable') is-invalid @enderror">
                    <option value="">— Sin asignar —</option>
                    @foreach($responsables as $user)
                        <option value="{{ $user->id }}"
                            {{ old('responsable') == $user->id ? 'selected' : '' }}>
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
                            {{ old('status_id') == $status->id_status ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
                @error('status_id')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="gf-btn gf-btn-primary">
                <i class="bi bi-check-lg"></i> Guardar característica
            </button>
            <a href="{{ route('caracteristicas.index') }}" class="gf-btn gf-btn-outline">
                <i class="bi bi-x"></i> Cancelar
            </a>
        </div>

    </form>
</div>

@endsection
