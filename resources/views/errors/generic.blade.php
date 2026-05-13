@extends('errors.layout')

@section('title', 'Error ' . ($status ?? ''))

@section('body')
    @php
        $info = [
            403 => ['icon' => 'bi-shield-lock',    'title' => 'Acceso no autorizado',       'msg'  => 'No tienes permiso para realizar esta acción. Contacta al administrador si crees que es un error.'],
            404 => ['icon' => 'bi-search',          'title' => 'Página no encontrada',        'msg'  => 'La página que buscas no existe o ha sido movida.'],
            405 => ['icon' => 'bi-sign-stop',       'title' => 'Método no permitido',         'msg'  => 'La acción que intentas realizar no está disponible de esta forma.'],
            419 => ['icon' => 'bi-clock-history',   'title' => 'Sesión expirada',             'msg'  => 'Tu sesión ha expirado. Por favor, vuelve a iniciar sesión para continuar.'],
            429 => ['icon' => 'bi-hourglass-split', 'title' => 'Demasiadas solicitudes',      'msg'  => 'Has realizado demasiadas solicitudes en poco tiempo. Espera unos momentos e intenta de nuevo.'],
            500 => ['icon' => 'bi-exclamation-octagon', 'title' => 'Error interno del servidor', 'msg' => 'Ocurrió un problema inesperado. El equipo técnico ha sido notificado.'],
            503 => ['icon' => 'bi-tools',           'title' => 'Servicio no disponible',      'msg'  => 'El sistema está en mantenimiento. Vuelve a intentarlo en unos minutos.'],
        ];
        $s    = $status ?? 500;
        $data = $info[$s] ?? ['icon' => 'bi-exclamation-circle', 'title' => 'Error inesperado', 'msg' => 'Ha ocurrido un error. Por favor, regresa al inicio.'];
    @endphp

    <div style="font-size:52px;color:var(--primary);margin-bottom:8px;">
        <i class="bi {{ $data['icon'] }}"></i>
    </div>
    <div class="error-code" style="font-size:52px;">{{ $s }}</div>
    <div class="error-divider"></div>
    <div class="error-title">{{ $data['title'] }}</div>
    <div class="error-message">{{ $data['msg'] }}</div>
    <div class="d-flex gap-2 justify-content-center">
        @if($s === 419)
            <a href="{{ route('login') }}" class="btn-gf-primary">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
            </a>
        @else
            <a href="javascript:history.back()" class="btn-gf-outline">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="{{ route('dashboard') }}" class="btn-gf-primary">
                <i class="bi bi-house"></i> Ir al inicio
            </a>
        @endif
    </div>
@endsection
