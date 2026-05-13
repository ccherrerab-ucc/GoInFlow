@extends('administrator.app')

@section('title', 'Mi perfil')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-2">
    <div>
        <div class="gf-page-title">Mi perfil</div>
        <div class="gf-page-sub">Administra tu información personal y contraseña de acceso</div>
    </div>
</div>

@if(session('status') === 'profile-updated')
<div class="gf-alert gf-alert-success">
    <i class="bi bi-check-circle"></i> Perfil actualizado correctamente.
</div>
@endif

@if(session('status') === 'password-updated')
<div class="gf-alert gf-alert-success">
    <i class="bi bi-check-circle"></i> Contraseña actualizada correctamente.
</div>
@endif

<div class="row g-4">

    {{-- ── Información personal ── --}}
    <div class="col-lg-7">
        <div class="gf-card">
            <div class="gf-card-title">
                <i class="bi bi-person-lines-fill"></i> Información personal
            </div>
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- ── Columna derecha ── --}}
    <div class="col-lg-5 d-flex flex-column gap-4">

        {{-- Avatar / resumen --}}
        <div class="gf-card text-center" style="padding:28px 24px;">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--primary-light);
                        border:2px solid var(--primary-border);display:flex;align-items:center;
                        justify-content:center;margin:0 auto 12px;font-size:28px;font-weight:700;
                        color:var(--primary);">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->first_surname ?? '', 0, 1)) }}
            </div>
            <div style="font-size:15px;font-weight:700;color:var(--gray-900);">
                {{ Auth::user()->name }} {{ Auth::user()->first_surname }} {{ Auth::user()->second_last_name }}
            </div>
            <div style="font-size:12px;color:var(--gray-400);margin-top:4px;">
                {{ Auth::user()->email }}
            </div>
            <div style="margin-top:10px;">
                <span class="gf-status gf-status-revision">
                    {{ Auth::user()->getRolNombre() }}
                </span>
            </div>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="gf-card">
            <div class="gf-card-title">
                <i class="bi bi-lock"></i> Cambiar contraseña
            </div>
            @include('profile.partials.update-password-form')
        </div>

    </div>
</div>

@endsection
