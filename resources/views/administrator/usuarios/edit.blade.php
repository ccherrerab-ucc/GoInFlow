@extends('administrator.app')

@section('title', 'Editar usuario')

@section('content')

<div class="gf-breadcrumb">
    <a href="{{ route('usuarios.index') }}">Usuarios</a>
    <span class="gf-breadcrumb-sep">
        <i class="bi bi-chevron-right" style="font-size:10px;"></i>
    </span>
    <span>Editar: {{ $user->name }}</span>
</div>

<div class="gf-page-title">Editar usuario</div>
<div class="gf-page-sub">Modifica los datos del usuario.</div>

<div class="gf-card" style="max-width:760px;">

<form method="POST" action="{{ route('usuarios.update', $user->id) }}">
    @csrf
    @method('PUT')

    {{-- Nombre --}}
    <div class="mb-3">
        <label class="gf-label">Nombre *</label>
        <input type="text" name="name"
            class="gf-input @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name) }}">
        @error('name')
            <div class="gf-field-error">{{ $message }}</div>
        @enderror
    </div>

    {{-- Apellidos --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="gf-label">Primer apellido *</label>
            <input type="text" name="first_surname"
                class="gf-input"
                value="{{ old('first_surname', $user->first_surname) }}">
        </div>
        <div class="col-md-6">
            <label class="gf-label">Segundo apellido</label>
            <input type="text" name="second_last_name"
                class="gf-input"
                value="{{ old('second_last_name', $user->second_last_name) }}">
        </div>
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label class="gf-label">Correo *</label>
        <input type="email" name="email"
            class="gf-input"
            value="{{ old('email', $user->email) }}">
    </div>

    {{-- Password (opcional) --}}
    <div class="mb-3">
        <label class="gf-label">Nueva contraseña</label>
        <input type="password" name="password" class="gf-input">
        <small style="font-size:11px;color:gray;">
            Dejar vacío si no deseas cambiarla
        </small>
    </div>

    {{-- Selects --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <label class="gf-label">Área</label>
            <select name="id_area" class="gf-select">
                <option value="">— Seleccionar —</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id_area }}"
                        {{ old('id_area', $user->id_area) == $area->id_area ? 'selected' : '' }}>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="gf-label">Departamento</label>
            <select name="id_departamento" class="gf-select">
                <option value="">— Seleccionar —</option>
                @foreach($departamentos as $d)
                    <option value="{{ $d->id_departamento }}"
                        {{ old('id_departamento', $user->id_departamento) == $d->id_departamento ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="gf-label">Rol *</label>
            <select name="id_rol" class="gf-select">
                <option value="">— Seleccionar —</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id_rol }}"
                        {{ old('id_rol', $user->id_rol) == $rol->id_rol ? 'selected' : '' }}>
                        {{ $rol->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="gf-label">Estado *</label>
            <select name="id_status" class="gf-select">
                <option value="">— Seleccionar —</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id_status }}"
                        {{ old('id_status', $user->id_status) == $status->id_status ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    {{-- Auditoría --}}
    <div style="background:var(--gray-50);border:1px solid var(--gray-100);border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:12px;color:var(--gray-600);">
        <div>
            <i class="bi bi-clock-history"></i>
            Creado: {{ $user->created_at?->format('d/m/Y H:i') }}
        </div>
        <div>
            <i class="bi bi-pencil-square"></i>
            Actualizado: {{ $user->updated_at?->format('d/m/Y H:i') }}
        </div>
    </div>

    {{-- Botones --}}
    <div class="d-flex gap-2">
        <button class="gf-btn gf-btn-primary">
            <i class="bi bi-check-lg"></i> Actualizar usuario
        </button>

        <a href="{{ route('usuarios.index') }}" class="gf-btn gf-btn-outline">
            Cancelar
        </a>
    </div>

</form>
</div>

@endsection