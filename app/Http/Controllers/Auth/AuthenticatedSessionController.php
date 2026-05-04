<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra el formulario de login.
     * Si el usuario ya está autenticado lo redirige directamente.
     * Headers no-cache para que el navegador nunca sirva esta página
     * desde caché (evita el 419 al presionar "atrás").
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectSegunRol(Auth::user());
        }

        return response()
            ->view('auth.login')
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
            ]);
    }

    /**
     * Procesa el intento de autenticación.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Cargar el rol antes de cualquier verificación (evita N+1)
        $user->load('rol');

        // Verificar que la cuenta esté activa
        if ($user->id_status != 1) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Tu cuenta está inactiva. Contacta al administrador.']);
        }

        return $this->redirectSegunRol($user);
    }

    /**
     * Cierra la sesión.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /* ── Helpers ─────────────────────────────────────────── */

    private function redirectSegunRol($user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('administrator.dashboard'));
        }

        if ($user->isDirector() || $user->isLiderCaracteristica() || $user->isEnlace()) {
            return redirect()->intended(route('dashboard'));
        }

        // Rol no reconocido — cerrar sesión y mostrar error
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors(['email' => 'Tu usuario no tiene un rol asignado. Contacta al administrador.']);
    }
}
