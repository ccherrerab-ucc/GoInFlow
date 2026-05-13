<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'rol' => \App\Http\Middleware\RolMiddleware::class,
        ]);
    })
    ->withProviders([
        App\Providers\AuthServiceProvider::class,        
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (
            \Throwable $e,
            \Illuminate\Http\Request $request
        ) {
            // 403: redirect to dashboard with modal message
            $is403 = $e instanceof \Illuminate\Auth\Access\AuthorizationException
                  || ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException
                      && $e->getStatusCode() === 403);

            if ($is403) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'No tienes permiso para realizar esta acción.',
                    ], 403);
                }
                return redirect()->route('dashboard')
                    ->with('forbidden', 'No tienes permisos para realizar esta acción. Contacta al administrador si crees que es un error.');
            }

            // All other HTTP exceptions: render friendly error view
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $status = $e->getStatusCode();

                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Error ' . $status], $status);
                }

                $view = view()->exists("errors.{$status}") ? "errors.{$status}" : 'errors.generic';
                return response()->view($view, ['status' => $status, 'exception' => $e], $status);
            }

            return null;
        });
    })
    ->create();
