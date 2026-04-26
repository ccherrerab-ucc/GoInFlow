<x-guest-layout>

    <!-- PANEL IZQUIERDO -->
    <div class="panel-brand">
        <i class="bi bi-folder2-open fs-1"></i>
        <h3>GoInFlow</h3>
        <p class="text-center px-3">
            Sistema de gestión documental para acreditación CNA
        </p>
    </div>

    <!-- PANEL DERECHO -->
    <div class="panel-form">

        <h4>Iniciar sesión</h4>
        <p class="text-muted">Ingresa con tu cuenta institucional</p>

        <!-- STATUS -->
        <x-auth-session-status class="mb-3 text-success" :status="session('status')" />

        <!-- ERROR GLOBAL -->
        @if ($errors->any())
            <div class="alert alert-danger">
                Credenciales incorrectas
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div class="mb-3">
                <label>Correo</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control-goinflow"
                    required
                >
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div class="mb-3">
                <label>Contraseña</label>
                <input
                    type="password"
                    name="password"
                    class="form-control-goinflow"
                    required
                >
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- REMEMBER -->
            <!--<div class="mb-3">
                <input type="checkbox" name="remember">
                Recordarme
            </div>-->

            <!-- FORGOT -->
            <!--@if (Route::has('password.request'))
                <div class="mb-3">
                    <a href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            @endif-->

            <!-- REGISTER -->
            @if (Route::has('register'))
                <div class="mb-3">
                    <span class="text-muted">¿No tienes cuenta?</span>
                    <a href="{{ route('register') }}">
                        Regístrate aquí
                    </a>
                </div>
            @endif

            <!-- BUTTON -->
            <button class="btn-goinflow">
                Ingresar
            </button>

        </form>

    </div>

</x-guest-layout>