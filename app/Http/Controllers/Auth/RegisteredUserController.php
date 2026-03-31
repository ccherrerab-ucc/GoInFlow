<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Area;
use App\Models\Departamento;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'areas' => Area::all(),
            'departamentos' => Departamento::all(), //collect(),//
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
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
            'id_rol' => '1',
        ]);

        event(new Registered($user));
        //dd($request->all());
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
