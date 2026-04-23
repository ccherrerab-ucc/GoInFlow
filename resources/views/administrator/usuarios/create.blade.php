@extends('administrator.app')

@section('title', 'Nuevo usuario')

@section('content')

<div class="gf-breadcrumb">
    <a href="{{ route('usuarios.index') }}">Usuarios</a>
    <span class="gf-breadcrumb-sep">
        <i class="bi bi-chevron-right" style="font-size:10px;"></i>
    </span>
    <span>Nuevo usuario</span>
</div>

<div class="gf-page-title">Nuevo usuario</div>
<div class="gf-page-sub">Completa los datos del usuario.</div>

<div class="gf-card" style="max-width:760px;">

<form method="POST" action="{{ route('usuarios.store') }}">
    @csrf

    {{-- Nombre --}}
    <div class="mb-3">
        <label class="gf-label">Nombre *</label>
        <input type="text" name="name"
            class="gf-input @error('name') is-invalid @enderror"
            value="{{ old('name') }}">
        @error('name')
            <div class="gf-field-error">{{ $message }}</div>
        @enderror
    </div>

    {{-- Apellidos --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="gf-label">Primer apellido *</label>
            <input type="text" name="first_surname"
                class="gf-input @error('first_surname') is-invalid @enderror"
                value="{{ old('first_surname') }}">
        </div>
        <div class="col-md-6">
            <label class="gf-label">Segundo apellido</label>
            <input type="text" name="second_last_name"
                class="gf-input"
                value="{{ old('second_last_name') }}">
        </div>
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label class="gf-label">Correo *</label>
        <input type="email" name="email"
            class="gf-input @error('email') is-invalid @enderror"
            value="{{ old('email') }}">
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <label class="gf-label">Contraseña *</label>
        <input type="password" name="password"
            class="gf-input @error('password') is-invalid @enderror">
    </div>

    {{-- Selects --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <label class="gf-label">Área</label>
            <select name="id_area" class="gf-select">
                <option value="">— Seleccionar —</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id_area }}">
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
                    <option value="{{ $d->id_departamento }}">
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
                    <option value="{{ $rol->id_rol }}">
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
                    <option value="{{ $status->id_status }}">
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    {{-- Botones --}}
    <div class="d-flex gap-2">
        <button class="gf-btn gf-btn-primary">
            <i class="bi bi-check-lg"></i> Guardar
        </button>

        <a href="{{ route('usuarios.index') }}" class="gf-btn gf-btn-outline">
            Cancelar
        </a>
    </div>

</form>
</div>

@endsection