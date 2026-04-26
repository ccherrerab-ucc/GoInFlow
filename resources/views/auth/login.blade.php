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
            Plataforma de gestión de evidencias para la mejora continua en procesos de calidad de la Universidad Catolica de Colombia.
        </p>

    </div>

    <!-- PANEL DERECHO -->
    <div class="  panel-form">

        <h4>Iniciar sesión</h4>
        <p class="text-muted">Ingresa con tu usuario y contraseña.</p>

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

            <!-- REGISTER 
            @if (Route::has('register'))
            <div class="mb-3">
                <span class="text-muted">¿No tienes cuenta?</span>
                <a href="{{ route('register') }}">
                    Regístrate aquí
                </a>
            </div>
            @endif-->


            <!-- LINKS: Olvidó contraseña + Registro -->
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    <!-- Botón que abre el modal -->
                    <a href="#" class="text-decoration-none small" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                        <i class="bi bi-question-circle me-1"></i>
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <div>
                    @if (Route::has('register'))
                    <span class="text-muted small">¿No tienes cuenta?</span>
                    <a href="{{ route('register') }}" class="text-decoration-none small fw-semibold">
                        Regístrate aquí
                    </a>
                    @endif
                </div>
            </div>
        </form>
        <br>
        <br>
        {{-- Logo Universidad --}}
        <img src="{{ asset('storage/images/Logos_White.png') }}" alt="Universidad"
            class="mb-5 logo-universidad">



    </div>

    <!-- MODAL: Olvidaste tu contraseña -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-semibold" id="forgotPasswordModalLabel">                        
                        ¡Recupera tu contraseña!
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body pt-2">
                    <p class="text-muted small mb-3">
                        Para recuperar tu contraseña, por favor envía un correo desde tu
                        <strong>correo institucional</strong> al administrador de EVIDENTIA solicitando el restablecimiento.
                    </p>

                    <div class="d-flex align-items-center gap-2 bg-light rounded-3 p-3">
                        <i class="bi bi-envelope-fill text-primary fs-5"></i>
                        <a href="mailto:adminEvidentia@ucatolica.edu.co" class="text-decoration-none fw-semibold small">
                            adminEvidentia@ucatolica.edu.co
                        </a>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-primary btn-sm " data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>

</x-guest-layout>