@extends('administrator.app')

@section('title', 'Editar característica')

@section('content')

<div class="gf-breadcrumb">
    <a href="{{ route('caracteristicas.index') }}">Características</a>
    <span class="gf-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:10px;"></i></span>
    <span>Editar: {{ $caracteristica->name }}</span>
</div>

<div class="gf-page-title">Editar característica</div>
<div class="gf-page-sub">Modifica los datos de la característica CNA.</div>

<div class="gf-card" style="max-width:760px;">

    <form method="POST" action="{{ route('caracteristicas.update', $caracteristica->id_caracteristica) }}">
        @csrf
        @method('PUT')

        {{-- Factor --}}
        <div class="mb-3">
            <label class="gf-label" for="factor_id">
                Factor <span style="color:var(--danger-text)">*</span>
            </label>
            <select id="factor_id"
                    name="factor_id"
                    class="gf-select @error('factor_id') is-invalid @enderror"
                    required>
                <option value="">— Seleccionar factor —</option>
                @foreach($factores as $factor)
                    <option value="{{ $factor->id_factor }}"
                        {{ old('factor_id', $caracteristica->factor_id) == $factor->id_factor ? 'selected' : '' }}>
                        {{ $factor->name }}
                    </option>
                @endforeach
            </select>
            @error('factor_id')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="gf-label" for="name">
                Nombre <span style="color:var(--danger-text)">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   class="gf-input @error('name') is-invalid @enderror"
                   value="{{ old('name', $caracteristica->name) }}"
                   placeholder="Ej. Característica 1 — Misión institucional"
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
                      class="gf-textarea @error('description') is-invalid @enderror"
                      placeholder="Descripción de la característica...">{{ old('description', $caracteristica->description) }}</textarea>
            @error('description')
                <div class="gf-field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Fechas --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="gf-label" for="fecha_inicio">
                    Fecha inicio <span style="color:var(--danger-text)">*</span>
                </label>
                <input type="datetime-local"
                       id="fecha_inicio"
                       name="fecha_inicio"
                       class="gf-input @error('fecha_inicio') is-invalid @enderror"
                       value="{{ old('fecha_inicio', $caracteristica->fecha_inicio?->format('Y-m-d\TH:i')) }}"
                       required>
                @error('fecha_inicio')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="gf-label" for="fecha_fin">
                    Fecha fin <span style="color:var(--danger-text)">*</span>
                </label>
                <input type="datetime-local"
                       id="fecha_fin"
                       name="fecha_fin"
                       class="gf-input @error('fecha_fin') is-invalid @enderror"
                       value="{{ old('fecha_fin', $caracteristica->fecha_fin?->format('Y-m-d\TH:i')) }}"
                       required>
                @error('fecha_fin')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Responsable y Estado --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="gf-label" for="responsable">Responsable</label>
                <select id="responsable"
                        name="responsable"
                        class="gf-select @error('responsable') is-invalid @enderror">
                    <option value="">— Sin asignar —</option>
                    @foreach($responsables as $user)
                        <option value="{{ $user->id }}"
                            {{ old('responsable', $caracteristica->responsable) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} {{ $user->first_surname }}
                        </option>
                    @endforeach
                </select>
                @error('responsable')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="gf-label" for="status_id">
                    Estado <span style="color:var(--danger-text)">*</span>
                </label>
                <select id="status_id"
                        name="status_id"
                        class="gf-select @error('status_id') is-invalid @enderror"
                        required>
                    <option value="">— Seleccionar —</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id_status }}"
                            {{ old('status_id', $caracteristica->status_id) == $status->id_status ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
                @error('status_id')
                    <div class="gf-field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ══ Flujo de aprobación ══ --}}
        <hr style="border-color:var(--gray-100);margin:8px 0 20px;">
        <div style="margin-bottom:6px;">
            <div style="font-size:14px;font-weight:700;color:var(--primary);">
                <i class="bi bi-diagram-3 me-2"></i>Flujo de aprobación
            </div>
            <div style="font-size:12px;color:var(--gray-500);margin-top:2px;">
                Roles que deben aprobar las evidencias, en orden. Los cambios aplican a nuevas evidencias;
                las que están en revisión activa no se ven afectadas.
            </div>
        </div>

        @php
            $flujoExistente = $caracteristica->flujoActivo;
            $pasosExistentes = old('flujo.pasos')
                ? collect(old('flujo.pasos'))->map(fn($p) => (object)$p)
                : ($flujoExistente?->pasos ?? collect());
        @endphp

        <div class="mb-3">
            <label class="gf-label" for="flujo_nombre">Nombre del flujo</label>
            <input type="text"
                   id="flujo_nombre"
                   name="flujo[nombre]"
                   class="gf-input"
                   value="{{ old('flujo.nombre', $flujoExistente?->nombre ?? 'Flujo de aprobación') }}"
                   placeholder="Ej. Aprobación estándar">
        </div>

        <div style="margin-bottom:8px;font-size:12px;font-weight:600;color:var(--gray-600);">
            Pasos del flujo <span style="font-weight:400;color:var(--gray-400);">(en orden de aprobación)</span>
        </div>

        <div id="pasos-container">
            @foreach($pasosExistentes as $i => $paso)
            <div class="paso-row d-flex align-items-center gap-2 mb-2">
                <span style="width:24px;text-align:center;font-size:13px;font-weight:700;
                             color:var(--primary);flex-shrink:0;" class="paso-orden">{{ $i + 1 }}</span>
                <select name="flujo[pasos][{{ $i }}][rol_requerido]"
                        class="gf-select" style="flex:1;" required>
                    <option value="">— Rol aprobador —</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id_rol }}"
                            {{ ($paso->rol_requerido ?? $paso['rol_requerido'] ?? null) == $rol->id_rol ? 'selected' : '' }}>
                            {{ $rol->name }}
                        </option>
                    @endforeach
                </select>
                <button type="button" class="gf-btn gf-btn-danger btn-quitar-paso"
                        style="height:34px;padding:0 10px;font-size:12px;flex-shrink:0;">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            @endforeach
        </div>

        <button type="button" id="btn-agregar-paso"
                class="gf-btn gf-btn-outline"
                style="font-size:12px;height:34px;padding:0 14px;margin-bottom:20px;">
            <i class="bi bi-plus-lg me-1"></i> Agregar paso
        </button>

        <template id="tpl-paso">
            <div class="paso-row d-flex align-items-center gap-2 mb-2">
                <span style="width:24px;text-align:center;font-size:13px;font-weight:700;
                             color:var(--primary);flex-shrink:0;" class="paso-orden"></span>
                <select name="" class="gf-select" style="flex:1;" required>
                    <option value="">— Rol aprobador —</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id_rol }}">{{ $rol->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="gf-btn gf-btn-danger btn-quitar-paso"
                        style="height:34px;padding:0 10px;font-size:12px;flex-shrink:0;">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </template>

        {{-- Info auditoría --}}
        <div style="background:var(--gray-50);border:1px solid var(--gray-100);border-radius:8px;
                    padding:12px 16px;margin-bottom:20px;font-size:12px;color:var(--gray-600);">
            <div style="margin-bottom:4px;">
                <i class="bi bi-clock-history" style="color:var(--primary);"></i>
                Creado por: <strong>{{ $caracteristica->creador?->name ?? 'Sistema' }}</strong>
                — {{ $caracteristica->created_at?->format('d/m/Y H:i') }}
            </div>
            <div>
                <i class="bi bi-pencil-square" style="color:var(--primary);"></i>
                Última actualización: <strong>{{ $caracteristica->actualizador?->name ?? 'Sistema' }}</strong>
                — {{ $caracteristica->updated_at?->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="gf-btn gf-btn-primary">
                <i class="bi bi-check-lg"></i> Actualizar característica
            </button>
            <a href="{{ route('caracteristicas.index') }}" class="gf-btn gf-btn-outline">
                <i class="bi bi-x"></i> Cancelar
            </a>
        </div>

    </form>
</div>

<script>
(function () {
    const container = document.getElementById('pasos-container');
    const tpl       = document.getElementById('tpl-paso');

    function reindexar() {
        container.querySelectorAll('.paso-row').forEach((row, i) => {
            row.querySelector('.paso-orden').textContent = i + 1;
            row.querySelector('select').name = `flujo[pasos][${i}][rol_requerido]`;
        });
    }

    document.getElementById('btn-agregar-paso').addEventListener('click', () => {
        const clone = tpl.content.cloneNode(true);
        container.appendChild(clone);
        reindexar();
    });

    container.addEventListener('click', (e) => {
        if (e.target.closest('.btn-quitar-paso')) {
            e.target.closest('.paso-row').remove();
            reindexar();
        }
    });
})();
</script>

@endsection
