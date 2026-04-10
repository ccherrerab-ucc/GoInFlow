@extends('administrator.app')

@section('title', 'Inicio')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-users me-2"></i>Gestión de Usuarios</h1>
            <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#userModal" id="createUserBtn">
                <i class="fas fa-user-plus me-2"></i>Crear Usuario
            </button>
        </div>
    </div>
</div>