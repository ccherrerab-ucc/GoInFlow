@extends('administrator.app')

@section('title', 'Nueva Evidencia')

@section('content')

<div class="gf-breadcrumb">
    <a href="{{ route('evidencias.index') }}">Evidencias</a>
    <span class="gf-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:10px;"></i></span>
    <span>Nueva evidencia</span>
</div>

<div class="gf-page-title">Nueva evidencia</div>
<div class="gf-page-sub">Registra un nuevo documento de evidencia para el proceso CNA. El estado inicial será <strong>Borrador</strong>.</div>

<div class="gf-card" style="max-width:760px;">

    <form method="POST" action="{{ route('evidencias.store') }}">
        @csrf

        {{-- Aspecto --}}
        <div class="mb-3">
            <label class="gf-label" for="id_aspecto">
                Aspecto <span style="color:var(--danger-text)">*</span>
            </label>
            <select id="id_aspecto"
                    name="id_aspecto"
                    class="gf-select @error('id_aspecto') is-invalid @enderror"
                    required>
                <option value="">— Seleccionar aspecto —</option>
                @foreach($aspectos as $aspecto)
                    <option value="{{ $aspecto->id_aspecto }}"
                        {{ old('id_aspecto') == $aspecto->id_aspecto ? 'selected' : '' }}>
                        {{ $aspecto->caracteristica?->name ? $aspecto->caracteristica->name . ' › ' : '' }}{{ $aspecto->name }}
                    </option>
                @endforeach
            </select>
            @error('id_aspecto')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="gf-label" for="nombre">
                Nombre <span style="color:var(--danger-text)">*</span>
            </label>
            <input type="text"
                   id="nombre"
                   name="nombre"
                   class="gf-input @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre') }}"
                   placeholder="Ej. Acta de reunión del comité curricular — marzo 2025"
                   required>
            @error('nombre')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label class="gf-label" for="descripcion">Descripción</label>
            <textarea id="descripcion"
                      name="descripcion"
                      rows="3"
                      class="gf-textarea @error('descripcion') is-invalid @enderror"
                      placeholder="Descripción del contenido y relevancia de esta evidencia...">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- URL OneDrive / SharePoint --}}
        <div class="mb-3">
            <label class="gf-label" for="url_evidencia">
                URL de evidencia en OneDrive / SharePoint
            </label>
            <input type="url"
                   id="url_evidencia"
                   name="url_evidencia"
                   class="gf-input @error('url_evidencia') is-invalid @enderror"
                   value="{{ old('url_evidencia') }}"
                   placeholder="https://miempresa.sharepoint.com/sites/...">
            @error('url_evidencia')
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
                <input type="date"
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
                        {{ old('status_id', 1) == $status->id_status ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
            @error('status_id')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="gf-btn gf-btn-primary">
                <i class="bi bi-check-lg"></i> Guardar evidencia
            </button>
            <a href="{{ route('evidencias.index') }}" class="gf-btn gf-btn-outline">
                <i class="bi bi-x"></i> Cancelar
            </a>
        </div>

    </form>
</div>

@endsection
