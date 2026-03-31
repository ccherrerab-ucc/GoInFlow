<x-guest-layout>
    <div class="login-wrapper">
        <div class="panel-form">
            <h2 class="mb-4" style="color:#0C447C;">Registro de Usuario</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nombres -->
                <div class="mb-3">
                    <x-input-label for="name" :value="__('Nombres *')" />
                    <x-text-input id="name" class="form-control form-control-goinflow" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-danger" />
                </div>

                <!-- Primer Apellido -->
                <div class="mb-3">
                    <x-input-label for="first_surname" :value="__('Primer Apellido *')" />
                    <x-text-input id="first_surname" class="form-control form-control-goinflow" type="text" name="first_surname" :value="old('first_surname')" required autocomplete="first_surname" />
                    <x-input-error :messages="$errors->get('first_surname')" class="mt-1 text-danger" />
                </div>

                <!-- Segundo Apellido -->
                <div class="mb-3">
                    <x-input-label for="second_last_name" :value="__('Segundo Apellido')" />
                    <x-text-input id="second_last_name" class="form-control form-control-goinflow" type="text" name="second_last_name" :value="old('second_last_name')" autocomplete="second_last_name" />
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="form-control form-control-goinflow" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="form-control form-control-goinflow" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="form-control form-control-goinflow" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-danger" />
                </div>

                <!-- Área -->
                <div class="mb-3">
                    <x-input-label for="id_area" value="Área" />
                    <select name="id_area" id="area-select" class="form-select form-control-goinflow" required>
                        <option value="">Seleccione un área</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('id_area')" class="mt-1 text-danger" />
                </div>

                <!-- Departamento -->
                <div class="mb-3">
                    <x-input-label for="id_departamento" value="Departamento" />
                    <select name="id_departamento" id="departamento-select" class="form-select form-control-goinflow" required>
                        <option value="">Seleccione un departamento</option>
                    </select>
                    <x-input-error :messages="$errors->get('id_departamento')" class="mt-1 text-danger" />
                </div>

                <!-- Errores globales -->
                @if ($errors->any())
                <div class="mb-3 text-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <!-- Botón Register -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a class="text-decoration-underline text-secondary" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="btn btn-goinflow ms-2">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <!-- AJAX para filtrar departamentos -->
    <script>
        document.getElementById('area-select').addEventListener('change', function() {
            let areaId = this.value;
            let departamentoSelect = document.getElementById('departamento-select');

            departamentoSelect.innerHTML = '<option value="">Cargando...</option>';

            fetch(`/departamentos/${areaId}`)
                .then(response => response.json())
                .then(data => {
                    departamentoSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
                    data.forEach(dep => {
                        let option = document.createElement('option');
                        option.value = dep.id_departamento;
                        option.textContent = dep.name;
                        departamentoSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    departamentoSelect.innerHTML = '<option value="">Error al cargar</option>';
                    console.error(error);
                });
        });
    </script>
</x-guest-layout>
<!--x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!d-- Name --d>
        <div>
            <x-input-label for="name" :value="__('Nombres *')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!d-- first_surname -d>
        <div class="mt-4">
            <x-input-label for="first_surname" :value="__('Primer Apellido *')" />
            <x-text-input id="first_surname" class="block mt-1 w-full" type="text" name="first_surname" :value="old('first_surname')" required autofocus autocomplete="first_surname" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!d-- second_last_name --d>
        <div class="mt-4">
            <x-input-label for="second_last_name" :value="__('Segundo Apellido')" />
            <x-text-input id="second_last_name" class="block mt-1 w-full" type="text" name="second_last_name" :value="old('second_last_name')" required autofocus autocomplete="second_last_name" />

        </div>

        <!-d- Email Address --d>
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-d- Password --d>
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-d- Confirm Password --d>
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="id_area" value="Área" />

            <select name="id_area" id="area-select" required>
                <option value="">Seleccione un área</option>
                @foreach($areas as $area)
                <option value="{{ $area->id_area }}">{{ $area->name }}</option>
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('id_area')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="id_departamento" value="Departamento" />

            <select name="id_departamento" id="departamento-select" required>
                <option value="">Seleccione un departamento</option>
            </select>

            <x-input-error :messages="$errors->get('id_departamento')" class="mt-2" />
        </div>


        @if ($errors->any())
        <div style="color:red;">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <script>
        document.getElementById('area-select').addEventListener('change', function() {
            let areaId = this.value;
            let departamentoSelect = document.getElementById('departamento-select');

            // Vaciar select
            departamentoSelect.innerHTML = '<option value="">Cargando...</option>';

            fetch(`/departamentos/${areaId}`)
                .then(response => response.json())
                .then(data => {
                    departamentoSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
                    data.forEach(dep => {
                        let option = document.createElement('option');
                        option.value = dep.id_departamento;
                        option.textContent = dep.name;
                        departamentoSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    departamentoSelect.innerHTML = '<option value="">Error al cargar</option>';
                    console.error(error);
                });
        });
    </script>
</x-guest-layout\*><!-d- comentario -->