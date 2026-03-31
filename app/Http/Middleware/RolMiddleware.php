<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
 
        // Sin sesión activa → login
        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Debes iniciar sesión para continuar.']);
        }
 
        // Cargar relación rol si no está cargada
        if (!$user->relationLoaded('rol')) {
            $user->load('rol');
        }
 
        // Verificar si el usuario tiene alguno de los roles permitidos
        $tieneRol = false;
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                $tieneRol = true;
                break;
            }
        }
 
        if ($tieneRol) {
            return $next($request);
        }
 
        // No tiene el rol requerido → redirigir a su dashboard correspondiente
        return $this->redirigirSegunRol($user);
    }

    private function redirigirSegunRol($user): Response
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->withErrors(['acceso' => 'No tienes permiso para esa sección.']);
        }
 
        if ($user->isDirector()) {
            return redirect()->route('director.dashboard')
                ->withErrors(['acceso' => 'No tienes permiso para esa sección.']);
        }
 
        if ($user->isLiderCaracteristica()) {
            return redirect()->route('lider.dashboard')
                ->withErrors(['acceso' => 'No tienes permiso para esa sección.']);
        }
 
        if ($user->isEnlace()) {
            return redirect()->route('enlace.dashboard')
                ->withErrors(['acceso' => 'No tienes permiso para esa sección.']);
        }
 
        // Rol desconocido → 403
        abort(403, 'Acceso no autorizado.');
    }
    

}
