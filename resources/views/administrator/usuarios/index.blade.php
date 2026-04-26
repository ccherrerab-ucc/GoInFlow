@extends('administrator.app')

@section('title', 'Factores CNA')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-2">
    <div>
        <div class="gf-page-title">Usuarios</div>
        <div class="gf-page-sub">Lista de usuarios</div>
    </div>
    <a href="{{ route('usuarios.create') }}" class="gf-btn gf-btn-primary"><!--Crear ruta-->
        <i class="bi bi-plus-lg"></i> Nuevo usuario
    </a>
</div>

<div class="gf-card p-0" style="overflow:hidden;">
    <div class="gf-table-scroll">
    <table class="gf-table gf-table-compact">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombres</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th>Correo electronico</th>
                <th>Estado</th>
                <th>Area</th>
                <th>Departamento</th>
                <th>Rol</th>
                <th>Acciones</th><!--restaurar contraseña-->
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td style="font-weight:500;">{{ $user->name }}</td>
                <td style="font-weight:500;">{{ $user->first_surname }}</td>
                <td style="font-weight:500;">{{ $user->second_last_name }}</td>
                <td style="font-weight:500;">{{ $user->email }}</td>
                <td>{{ $user->status?->name ?? '—' }}</td>
                <td>{{ $user->area?->name ?? '—' }}</td>
                <td>{{ $user->department?->name ?? '—' }}</td>
                <td>{{ $user->role?->name ?? '—' }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('usuarios.edit', $user->id) }}"
                            class="gf-btn gf-btn-outline" style="height:30px;padding:0 10px;font-size:12px;">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('usuarios.destroy', $user->id) }}"
                            method="POST"
                            onsubmit="return confirm('¿Eliminar este usuario?')">
                            @csrf @method('DELETE') <!--Crear ruta eliminar pero realmente se va inactivar y no se podrá observar desde el sistema-->
                            <button type="submit"
                                class="gf-btn gf-btn-danger"
                                style="height:30px;padding:0 10px;font-size:12px;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:32px;color:var(--gray-400);">
                    <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                    No hay factores registrados.
                    <a href="{{ route('factores.create') }}" style="color:var(--primary-mid);">Crear el primero</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@endsection