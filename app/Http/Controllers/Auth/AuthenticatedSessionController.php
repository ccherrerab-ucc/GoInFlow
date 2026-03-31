<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        assert($user instanceof User);
        
        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'No se pudo obtener el usuario autenticado.'+ $user->id]);
        }

        // Cargar la relación rol de una sola vez para evitar N+1
        $nuevavariable =$user->load('rol');

        // Verificar que el usuario esté activo (id_status)
        // Ajusta el valor según cómo tengas definido "activo" en tu tabla Status
        if ($user->id_status != 1) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Tu cuenta está inactiva. Contacta al administrador.'+ $nuevavariable ]);
        }

        // Redirigir según rol
        if ($user->isAdmin()) {
            return redirect()->intended(route('dashboard')); //('admin.dashboard'));
        }

        if ($user->isDirector()) {
            return redirect()->intended(route('dashboard')); //('director.dashboard'));
        }

        if ($user->isLiderCaracteristica()) {
            return redirect()->intended(route('dashboard')); //('lider.dashboard'));
        }

        if ($user->isEnlace()) {
            return redirect()->intended(route('dashboard')); //('enlace.dashboard'));
        }

        // Si el usuario existe pero no tiene rol válido reconocido
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors(['email' => 'Tu usuario no tiene un rol asignado. Contacta al administrador.' . $nuevavariable ]);
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
