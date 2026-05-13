@extends('errors.layout')

@section('title', 'Página no encontrada')

@section('body')
    <div class="error-code">404</div>
    <div class="error-divider"></div>
    <div class="error-title">Página no encontrada</div>
    <div class="error-message">
        La página que buscas no existe o ha sido movida.<br>
        Verifica la dirección e intenta de nuevo.
    </div>
    <div class="d-flex gap-2 justify-content-center">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="btn-gf-outline">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
        <a href="{{ route('dashboard') }}" class="btn-gf-primary">
            <i class="bi bi-house"></i> Ir al inicio
        </a>
    </div>
@endsection
