@extends('administrator.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid py-4">

    <!-- 🔹 RESUMEN -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Factores</h6>
                    <h3>{{ $factores->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Características</h6>
                    <h3>{{ $caracteristicas->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Aspectos</h6>
                    <h3>{{ $aspectos->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!--div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Evidencias</h6>
                    <h3>{{ $evidencias->count() ?? 0 }}</h3>
                </div>
            </div>
        </!--div-->
    </div>

    <!-- 🔹 ACCIONES -->
    <div class="d-flex justify-content-between mb-3">
        <h4>Gestión CNA</h4>

        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCaracteristica">
                + Característica
            </button>

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAspecto">
                + Aspecto
            </button>
        </div>
    </div>

    <!-- 🔹 LISTADO FACTORES -->
    <div class="card shadow-sm">
        <div class="card-header">
            <strong>Factores</strong>
        </div>

        <div class="card-body">
            <div class="accordion" id="accordionFactor">

                @foreach($factores as $factor)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#factor{{ $factor->id_factor }}">
                            {{ $factor->nombre }}
                        </button>
                    </h2>

                    <div id="factor{{ $factor->id_factor }}" class="accordion-collapse collapse">
                        <div class="accordion-body">

                            <!-- Características -->
                            @foreach($factor->caracteristicas as $caracteristica)
                                <div class="mb-3">
                                    <strong>{{ $caracteristica->nombre }}</strong>

                                    <!-- Aspectos -->
                                    <ul class="mt-2">
                                        @foreach($caracteristica->aspectos as $aspecto)
                                            <li>{{ $aspecto->nombre }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

</div>

<!-- 🔥 MODAL CREAR CARACTERISTICA -->
<div class="modal fade" id="modalCaracteristica">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('caracteristica.store') }}" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5>Crear Característica</h5>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Factor</label>
                    <select name="id_factor" class="form-control">
                        @foreach($factores as $factor)
                            <option value="{{ $factor->id_factor }}">
                                {{ $factor->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary">Guardar</button>
            </div>

        </form>
    </div>
</div>

<!-- 🔥 MODAL CREAR ASPECTO -->
<div class="modal fade" id="modalAspecto">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('aspecto.store') }}" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5>Crear Aspecto</h5>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Característica</label>
                    <select name="id_caracteristica" class="form-control">
                        @foreach($caracteristicas as $c)
                            <option value="{{ $c->id_caracteristica }}">
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success">Guardar</button>
            </div>

        </form>
    </div>
</div>

@endsection