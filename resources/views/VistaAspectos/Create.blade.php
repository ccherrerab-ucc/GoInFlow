@extends('administrator.app')

@section('title', 'Nuevo aspecto')

@section('content')

<div class="gf-breadcrumb">
    <a href="{{ route('aspectos.index') }}">Aspectos</a>
    <span class="gf-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:10px;"></i></span>
    <span>Nuevo aspecto</span>
</div>

<div class="gf-page-title">Nuevo aspecto por evaluar</div>
<div class="gf-page-sub">Asocia el aspecto a la característica correspondiente.</div>

<div class="gf-card" style="max-width:760px;">

    <form method="POST" action="{{ route('aspectos.store') }}">
        @csrf

        {{-- Característica (con Factor agrupado) --}}
        <div class="mb-3">
            <label class="gf-label" for="caracteristica_id">
                Característica <span style="color:var(--danger-text)">*</span>
            </label>
            <select id="caracteristica_id"
                    name="caracteristica_id"
                    class="gf-select @error('caracteristica_id') is-invalid @enderror"
                    required>
                <option value="">— Seleccionar característica —</option>
                @foreach($caracteristicas->groupBy(fn($c) => $c->factor?->name ?? 'Sin factor') as $factorNombre => $grupo)
                    <optgroup label="{{ $factorNombre }}">
                        @foreach($grupo as $caracteristica)
                            <option value="{{ $caracteristica->id_caracteristica }}"
                                {{ old('caracteristica_id') == $caracteristica->id_caracteristica ? 'selected' : '' }}>
                                {{ $caracteristica->name }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('caracteristica_id')
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
                   placeholder="Ej. Aspecto 3.2 — Formación docente"
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
                      placeholder="Descripción del aspecto por evaluar...">{{ old('description') }}</textarea>
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
                <i class="bi bi-check-lg"></i> Guardar aspecto
            </button>
            <a href="{{ route('aspectos.index') }}" class="gf-btn gf-btn-outline">
                <i class="bi bi-x"></i> Cancelar
            </a>
        </div>

    </form>
</div>

@endsection
