@extends('administrator.app')

@section('title', 'Nuevo resultado')

@section('content')

<div class="gf-breadcrumb">
    <a href="{{ route('resultados.index') }}">Resultados</a>
    <span class="gf-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:10px;"></i></span>
    <span>Nuevo resultado</span>
</div>

<div class="gf-page-title">Nuevo resultado</div>
<div class="gf-page-sub">Registra un resultado asociado a un factor, característica o aspecto del CNA.</div>

<div class="gf-card" style="max-width:760px;">

    <form method="POST" action="{{ route('resultados.store') }}">
        @csrf

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="gf-label" for="name">
                Nombre <span style="color:var(--danger-text)">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   class="gf-input @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   placeholder="Ej. Resultado de aprendizaje 1 — Pensamiento crítico"
                   required>
            @error('name')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label class="gf-label" for="description">Descripción</label>
            <textarea id="description"
                      name="description"
                      rows="3"
                      class="gf-textarea @error('description') is-invalid @enderror"
                      placeholder="Descripción del resultado esperado...">{{ old('description') }}</textarea>
            @error('description')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tipo relación + Entidad --}}
        <div class="row g-3 mb-3">
            <div class="col-md-5">
                <label class="gf-label" for="tipo_relacion">
                    Tipo de relación <span style="color:var(--danger-text)">*</span>
                </label>
                <select id="tipo_relacion"
                        name="tipo_relacion"
                        class="gf-select @error('tipo_relacion') is-invalid @enderror"
                        required>
                    <option value="">— Seleccionar tipo —</option>
                    @foreach($tiposRelacion as $tipo)
                        <option value="{{ $tipo }}"
                            {{ old('tipo_relacion') === $tipo ? 'selected' : '' }}>
                            {{ ucfirst($tipo) }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_relacion')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-7">
                <label class="gf-label" for="id_referencia">
                    Entidad relacionada <span style="color:var(--danger-text)">*</span>
                </label>

                {{-- Factor select --}}
                <select id="ref_factor"
                        name="id_referencia"
                        class="gf-select ref-select @error('id_referencia') is-invalid @enderror"
                        style="display:none;">
                    <option value="">— Seleccionar factor —</option>
                    @foreach($factores as $f)
                        <option value="{{ $f->id_factor }}"
                            {{ old('id_referencia') == $f->id_factor && old('tipo_relacion') === 'factor' ? 'selected' : '' }}>
                            {{ $f->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Característica select --}}
                <select id="ref_caracteristica"
                        name="id_referencia"
                        class="gf-select ref-select @error('id_referencia') is-invalid @enderror"
                        style="display:none;">
                    <option value="">— Seleccionar característica —</option>
                    @foreach($caracteristicas as $c)
                        <option value="{{ $c->id_caracteristica }}"
                            {{ old('id_referencia') == $c->id_caracteristica && old('tipo_relacion') === 'caracteristica' ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Aspecto select --}}
                <select id="ref_aspecto"
                        name="id_referencia"
                        class="gf-select ref-select @error('id_referencia') is-invalid @enderror"
                        style="display:none;">
                    <option value="">— Seleccionar aspecto —</option>
                    @foreach($aspectos as $a)
                        <option value="{{ $a->id_aspecto }}"
                            {{ old('id_referencia') == $a->id_aspecto && old('tipo_relacion') === 'aspecto' ? 'selected' : '' }}>
                            {{ $a->caracteristica?->name ? $a->caracteristica->name . ' › ' : '' }}{{ $a->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Placeholder when no type selected --}}
                <div id="ref_placeholder" class="gf-input"
                     style="color:var(--gray-400);cursor:default;user-select:none;">
                    Selecciona primero el tipo
                </div>

                @error('id_referencia')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Fechas --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="gf-label" for="fecha_inicio">
                    Fecha inicio <span style="color:var(--danger-text)">*</span>
                </label>
                <input type="date"
                       id="fecha_inicio"
                       name="fecha_inicio"
                       class="gf-input @error('fecha_inicio') is-invalid @enderror"
                       value="{{ old('fecha_inicio') }}"
                       required>
                @error('fecha_inicio')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="gf-label" for="fecha_fin">
                    Fecha fin <span style="color:var(--danger-text)">*</span>
                </label>
                <input type="date"
                       id="fecha_fin"
                       name="fecha_fin"
                       class="gf-input @error('fecha_fin') is-invalid @enderror"
                       value="{{ old('fecha_fin') }}"
                       required>
                @error('fecha_fin')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Estado CNA --}}
        <div class="mb-4">
            <label class="gf-label" for="status_id">
                Estado CNA <span style="color:var(--danger-text)">*</span>
            </label>
            <select id="status_id"
                    name="status_id"
                    class="gf-select @error('status_id') is-invalid @enderror"
                    required>
                <option value="">— Seleccionar —</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id_status }}"
                        {{ old('status_id', 1) == $status->id_status ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
            @error('status_id')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="gf-btn gf-btn-primary">
                <i class="bi bi-check-lg"></i> Guardar resultado
            </button>
            <a href="{{ route('resultados.index') }}" class="gf-btn gf-btn-outline">
                <i class="bi bi-x"></i> Cancelar
            </a>
        </div>

    </form>
</div>

<script>
(function () {
    const tipoSelect = document.getElementById('tipo_relacion');
    const refMap = {
        factor:         document.getElementById('ref_factor'),
        caracteristica: document.getElementById('ref_caracteristica'),
        aspecto:        document.getElementById('ref_aspecto'),
    };
    const placeholder = document.getElementById('ref_placeholder');

    function showRef(tipo) {
        placeholder.style.display = 'none';
        Object.entries(refMap).forEach(([key, el]) => {
            const show = key === tipo;
            el.style.display = show ? '' : 'none';
            el.disabled = !show;
            el.required = show;
        });
        if (!tipo) {
            placeholder.style.display = '';
        }
    }

    tipoSelect.addEventListener('change', () => showRef(tipoSelect.value));

    // Init on page load (handles old() repopulation after validation failure)
    showRef(tipoSelect.value);
})();
</script>

@endsection
