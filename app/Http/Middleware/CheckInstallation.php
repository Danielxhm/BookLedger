<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isInstalled = file_exists(storage_path('installed'));
        $isInstallRoute = $request->is('install') || $request->is('install/*');

        // Si ya está instalado y el usuario intenta entrar al instalador, bloquear el acceso
        if ($isInstalled && $isInstallRoute) {
            if ($request->is('install/success')) {
                // Permitir ver la pantalla de éxito inmediatamente después de instalar si viene con sesión
                if (session('installation_completed')) {
                    return $next($request);
                }
            }

            return redirect()->route('login');
        }

        // Si NO está instalado y no está en la ruta del instalador, redirigir a la instalación
        if (!$isInstalled && !$isInstallRoute) {
            // Permitir assets estáticos y rutas de salud
            if ($request->is('build/*') || $request->is('images/*') || $request->is('up') || $request->is('favicon.ico')) {
                return $next($request);
            }

            return redirect()->route('install.index');
        }

        return $next($request);
    }
}
