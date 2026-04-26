@extends('administrator.app')

@section('title', 'Editar resultado')

@section('content')

<div class="gf-breadcrumb">
    <a href="{{ route('resultados.index') }}">Resultados</a>
    <span class="gf-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:10px;"></i></span>
    <span>Editar: {{ $resultado->name }}</span>
</div>

<div class="gf-page-title">Editar resultado</div>
<div class="gf-page-sub">Modifica los datos del resultado CNA.</div>

<div class="gf-card" style="max-width:760px;">

    <form method="POST" action="{{ route('resultados.update', $resultado->id_resultado) }}">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="gf-label" for="name">
                Nombre <span style="color:var(--danger-text)">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   class="gf-input @error('name') is-invalid @enderror"
                   value="{{ old('name', $resultado->name) }}"
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
                      placeholder="Descripción del resultado esperado...">{{ old('description', $resultado->description) }}</textarea>
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
                            {{ old('tipo_relacion', $resultado->tipo_relacion) === $tipo ? 'selected' : '' }}>
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
                            {{ old('id_referencia', $resultado->id_referencia) == $f->id_factor && old('tipo_relacion', $resultado->tipo_relacion) === 'factor' ? 'selected' : '' }}>
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
                            {{ old('id_referencia', $resultado->id_referencia) == $c->id_caracteristica && old('tipo_relacion', $resultado->tipo_relacion) === 'caracteristica' ? 'selected' : '' }}>
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
                            {{ old('id_referencia', $resultado->id_referencia) == $a->id_aspecto && old('tipo_relacion', $resultado->tipo_relacion) === 'aspecto' ? 'selected' : '' }}>
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
                       value="{{ old('fecha_inicio', $resultado->fecha_inicio ? \Carbon\Carbon::parse($resultado->fecha_inicio)->format('Y-m-d') : '') }}"
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
                       value="{{ old('fecha_fin', $resultado->fecha_fin ? \Carbon\Carbon::parse($resultado->fecha_fin)->format('Y-m-d') : '') }}"
                       required>
                @error('fecha_fin')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Estado CNA --}}
        <div class="mb-3">
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
                        {{ old('status_id', $resultado->status_id) == $status->id_status ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
            @error('status_id')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Info auditoría --}}
        <div style="background:var(--gray-50);border:1px solid var(--gray-100);border-radius:8px;
                    padding:12px 16px;margin-bottom:20px;font-size:12px;color:var(--gray-600);">
            <div style="margin-bottom:4px;">
                <i class="bi bi-clock-history" style="color:var(--primary);"></i>
                Creado por: <strong>{{ $resultado->createdBy?->name ?? 'Sistema' }}</strong>
                — {{ $resultado->created_at?->format('d/m/Y H:i') }}
            </div>
            <div>
                <i class="bi bi-pencil-square" style="color:var(--primary);"></i>
                Última actualización: <strong>{{ $resultado->updatedBy?->name ?? 'Sistema' }}</strong>
                — {{ $resultado->updated_at?->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="gf-btn gf-btn-primary">
                <i class="bi bi-check-lg"></i> Actualizar resultado
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
    showRef(tipoSelect.value);
})();
</script>

@endsection
