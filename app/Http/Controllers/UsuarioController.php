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
        $users = User::with(['area', 'departamento', 'rol', 'status'])->get();

        return view('administrator.usuarios.index', compact('users'));
    }

    /*public function create(): View
    {
        return view('administrator.usuarios.create');
    }*/
    public function create()
    {
        return view('administrator.usuarios.create', [
            'areas' => Area::all(),
            'departamentos' => Departamento::all(),
            'roles' => Rol::all(),
            'statuses' => Status::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'id_area' => ['required', 'exists:d_area,id_area'],
            'id_departamento' => ['required', 'exists:departamento,id_departamento'],
            'first_surname' => ['nullable', 'string', 'max:255'],
            'second_last_name' => ['nullable', 'string', 'max:255'],
        
        ]);

        $user = User::create([
            'name' => $request->name,
            'first_surname' => $request->first_surname,
            'second_last_name' => $request->second_last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_area' => $request->id_area,
            'id_departamento' => $request->id_departamento,
            'id_status' => '1',
            'id_rol' => '4',
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado');
    }

    public function edit(int $id): View
    {
        $user = User::findOrFail($id);

        //return view('administrator.usuarios.edit', compact('user'));
        return view('administrator.usuarios.edit', [
            'user' => User::findOrFail($id),
            'areas' => Area::all(),
            'departamentos' => Departamento::all(),
            'roles' => Rol::all(),
            'statuses' => Status::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->all();

        // ⚠️ Si no escribe password → no lo actualiza
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado');
    }


    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // ⚠️ mejor desactivar que eliminar
        $user->update([
            'id_status' => 2 // ejemplo: inactivo
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario inactivado');
    }
}
