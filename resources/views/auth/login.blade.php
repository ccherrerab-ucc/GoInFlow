<x-guest-layout>

    <!-- PANEL IZQUIERDO -->
    <div class="panel-brand">
        {{-- Logo EVIDENTIA --}}
        <img src="{{ asset('storage/images/EVIDENTIA.png') }}" alt="Universidad"
            class="mb-3 logo-universidad">


        {{-- Título --}}
        <h3 class="fw-bold mt-2 mb-0">EVIDENTIA</h3>

        {{-- Subtítulo --}}
        <p class="text-center px-3 mb-1 small fw-light">
            Evidencias para la calidad PS&S
        </p>

        {{-- Descripción --}}
        <p class="text-center px-4 small opacity-75 mb-0">
            Plataforma de gestión de evidencias para la mejora continua en procesos de calidad.
        </p>

    </div>

    <!-- PANEL DERECHO -->
    <div class="  panel-form">

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
                    required>
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
                    required>
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

            <!-- BUTTON -->
            <button class="btn-goinflow">
                Ingresar
            </button>

            <!-- REGISTER -->
            @if (Route::has('register'))
            <div class="mb-3">
                <span class="text-muted">¿No tienes cuenta?</span>
                <a href="{{ route('register') }}">
                    Regístrate aquí
                </a>
            </div>
            @endif
            <br>
            <br>
            {{-- Logo Universidad --}}
            <img src="{{ asset('storage/images/Logos_White.png') }}" alt="Universidad"
                class="mb-5 logo-universidad">

        </form>

    </div>

</x-guest-layout>