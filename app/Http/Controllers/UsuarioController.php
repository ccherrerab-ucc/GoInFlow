<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Area;
use App\Models\Departamento;
use App\Models\Rol;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::with(['area', 'departamento', 'rol', 'status'])->get();

        return view('administrator.usuarios.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('administrator.usuarios.create', [
            'areas'         => Area::all(),
            'departamentos' => Departamento::all(),
            'roles'         => Rol::all(),
            'statuses'      => Status::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $request->validate([
            'name'             => 'required',
            'email'            => 'required|email|unique:users',
            'password'         => 'required|min:6',
            'id_area'          => ['required', 'exists:d_area,id_area'],
            'id_departamento'  => ['required', 'exists:departamento,id_departamento'],
            'first_surname'    => ['nullable', 'string', 'max:255'],
            'second_last_name' => ['nullable', 'string', 'max:255'],
        ]);

        User::create([
            'name'             => $request->name,
            'first_surname'    => $request->first_surname,
            'second_last_name' => $request->second_last_name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'id_area'          => $request->id_area,
            'id_departamento'  => $request->id_departamento,
            'id_status'        => 1,
            'id_rol'           => 4,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(int $id): View
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('administrator.usuarios.edit', [
            'user'          => $user,
            'areas'         => Area::all(),
            'departamentos' => Departamento::all(),
            'roles'         => Rol::all(),
            'statuses'      => Status::all(),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $data = $request->all();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $user->update(['id_status' => 2]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario inactivado exitosamente.');
    }
}
