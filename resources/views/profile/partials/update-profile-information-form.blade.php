<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="row g-3">

        <div class="col-12">
            <label class="gf-label" for="name">Nombres</label>
            <input id="name" name="name" type="text" class="gf-input"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6">
            <label class="gf-label" for="first_surname">Primer apellido</label>
            <input id="first_surname" name="first_surname" type="text" class="gf-input"
                   value="{{ old('first_surname', $user->first_surname) }}" required autocomplete="family-name">
            @error('first_surname')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6">
            <label class="gf-label" for="second_last_name">Segundo apellido</label>
            <input id="second_last_name" name="second_last_name" type="text" class="gf-input"
                   value="{{ old('second_last_name', $user->second_last_name) }}" autocomplete="off">
            @error('second_last_name')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label class="gf-label" for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" class="gf-input"
                   value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6">
            <label class="gf-label" for="area-select">Área</label>
            <select id="area-select" name="id_area" class="gf-select" required>
                <option value="">Seleccione un área</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id_area }}"
                        {{ old('id_area', $user->id_area ?? '') == $area->id_area ? 'selected' : '' }}>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
            @error('id_area')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6">
            <label class="gf-label" for="departamento-select">Departamento</label>
            <select id="departamento-select" name="id_departamento" class="gf-select" required>
                <option value="">Seleccione un departamento</option>
                @if(isset($departamentos))
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->id_departamento }}"
                            {{ old('id_departamento', $user->id_departamento ?? '') == $departamento->id_departamento ? 'selected' : '' }}>
                            {{ $departamento->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            @error('id_departamento')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

    </div>

    <div class="mt-4">
        <button type="submit" class="gf-btn gf-btn-primary">
            <i class="bi bi-floppy"></i> Guardar cambios
        </button>
    </div>

</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const areaSelect        = document.getElementById('area-select');
    const departamentoSelect = document.getElementById('departamento-select');
    const selectedDep       = "{{ old('id_departamento', $user->id_departamento ?? '') }}";

    function loadDepartamentos(areaId, selectValue) {
        if (!areaId) {
            departamentoSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
            return;
        }
        departamentoSelect.innerHTML = '<option value="">Cargando...</option>';
        fetch(`/departamentos/${areaId}`)
            .then(r => r.json())
            .then(data => {
                departamentoSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
                data.forEach(dep => {
                    const opt = document.createElement('option');
                    opt.value       = dep.id_departamento;
                    opt.textContent = dep.name;
                    if (dep.id_departamento == selectValue) opt.selected = true;
                    departamentoSelect.appendChild(opt);
                });
            })
            .catch(() => {
                departamentoSelect.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    areaSelect.addEventListener('change', function () {
        loadDepartamentos(this.value, null);
    });

    if (areaSelect.value) {
        loadDepartamentos(areaSelect.value, selectedDep);
    }
});
</script>
@endpush
