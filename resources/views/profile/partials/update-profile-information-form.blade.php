<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Información del perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Puedes actualizar la información de tu perfil.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div>
            <x-input-label for="first_surname" :value="__('Primer Apellido')" />
            <x-text-input id="first_surname" name="first_surname" type="text" class="mt-1 block w-full" :value="old('first_surname', $user->first_surname)" required autofocus autocomplete="first_surname" />
            <x-input-error class="mt-2" :messages="$errors->get('first_surname')" />
        </div>
        <div>
            <x-input-label for="second_last_name" :value="__('Segundo Apellido')" />
            <x-text-input id="second_last_name" name="second_last_name" type="text" class="mt-1 block w-full" :value="old('second_last_name', $user->second_last_name)" />
            <x-input-error class="mt-2" :messages="$errors->get('second_last_name')" />
        </div>
        
        <div>
            <x-input-label for="id_area" :value="__('Área')" />
            <select id="area-select" name="id_area" class="mt-1 block w-full" required>
                <option value="">{{ __('Seleccione un área') }}</option>
                @foreach($areas as $area)
                <option value="{{ $area->id_area }}"
                    {{ old('id_area', $user->id_area ?? '') == $area->id_area ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('id_area')" />
        </div>

        <div>
            <x-input-label for="id_departamento" :value="__('Departamento')" />
            <select id="departamento-select" name="id_departamento" class="mt-1 block w-full" required>
                <option value="">{{ __('Seleccione un departamento') }}</option>
                @if(isset($departamentos))
                @foreach($departamentos as $departamento)
                <option value="{{ $departamento->id_departamento }}"
                    {{ old('id_departamento', $user->id_departamento ?? '') == $departamento->id_departamento ? 'selected' : '' }}>
                    {{ $departamento->name }}
                </option>
                @endforeach
                @endif
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('id_departamento')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
    <!--script>
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
    </!--script-->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const areaSelect = document.getElementById('area-select');
            const departamentoSelect = document.getElementById('departamento-select');

            // Valor inicial de departamento (old() o $user)
            const selectedDepartamento = "{{ old('id_departamento', $user->id_departamento ?? '') }}";

            areaSelect.addEventListener('change', function() {
                const areaId = this.value;

                departamentoSelect.innerHTML = '<option value="">Cargando...</option>';

                if (!areaId) {
                    departamentoSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
                    return;
                }

                fetch(`/departamentos/${areaId}`)
                    .then(response => response.json())
                    .then(data => {
                        departamentoSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
                        data.forEach(dep => {
                            const option = document.createElement('option');
                            option.value = dep.id_departamento;
                            option.textContent = dep.name;

                            // Selecciona automáticamente si coincide con old() o $user
                            if (dep.id_departamento == selectedDepartamento) {
                                option.selected = true;
                            }

                            departamentoSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        departamentoSelect.innerHTML = '<option value="">Error al cargar</option>';
                        console.error(error);
                    });
            });

            // Disparar change al cargar la página si ya hay un área seleccionada
            if (areaSelect.value) {
                areaSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</section>