@extends('administrator.app')
 
@section('title', 'Editar factor')
 
@section('content')
 
<div class="gf-breadcrumb">
    <a href="{{ route('factores.index') }}">Factores</a>
    <span class="gf-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:10px;"></i></span>
    <span>Editar: {{ $factor->name }}</span>
</div>
 
<div class="gf-page-title">Editar factor</div>
<div class="gf-page-sub">Modifica los datos del factor CNA.</div>
 
<div class="gf-card" style="max-width:760px;">
 
    <form method="POST" action="{{ route('factores.update', $factor->id_factor) }}">
        @csrf
        @method('PUT')
 
        {{-- Nombre --}}
        <div class="mb-3">
            <label class="gf-label" for="name">
                Nombre del factor <span style="color:var(--danger-text)">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   class="gf-input @error('name') is-invalid @enderror"
                   value="{{ old('name', $factor->name) }}"
                   placeholder="Ej. Factor 5 — Estructura académica"
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
                      placeholder="Descripción del factor...">{{ old('description', $factor->description) }}</textarea>
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
                       value="{{ old('fecha_inicio', $factor->fecha_inicio?->format('Y-m-d\TH:i')) }}"
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
                       value="{{ old('fecha_fin', $factor->fecha_fin?->format('Y-m-d\TH:i')) }}"
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
                            {{ old('responsable', $factor->responsable) == $user->id ? 'selected' : '' }}>
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
                            {{ old('status_id', $factor->status_id) == $status->id_status ? 'selected' : '' }}>
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
        <div style="background:var(--gray-50);border:1px solid var(--gray-100);border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:12px;color:var(--gray-600);">
            <div style="margin-bottom:4px;">
                <i class="bi bi-clock-history" style="color:var(--primary);"></i>
                Creado por: <strong>{{ $factor->creador?->name ?? 'Sistema' }}</strong>
                — {{ $factor->created_at?->format('d/m/Y H:i') }}
            </div>
            <div>
                <i class="bi bi-pencil-square" style="color:var(--primary);"></i>
                Última actualización: <strong>{{ $factor->actualizador?->name ?? 'Sistema' }}</strong>
                — {{ $factor->updated_at?->format('d/m/Y H:i') }}
            </div>
        </div>
 
        {{-- Acciones --}}
        <div class="d-flex gap-2">
            <button type="submit" class="gf-btn gf-btn-primary">
                <i class="bi bi-check-lg"></i> Actualizar factor
            </button>
            <a href="{{ route('factores.index') }}" class="gf-btn gf-btn-outline">
                <i class="bi bi-x"></i> Cancelar
            </a>
        </div>
 
    </form>
</div>
 
@endsection