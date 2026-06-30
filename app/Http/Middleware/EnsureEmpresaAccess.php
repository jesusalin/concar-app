<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmpresaAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $empresa = $request->route('empresa');

        if ($empresa instanceof Empresa) {
            $rol = $empresa->rolDe($request->user()->id);

            if (! $rol) {
                abort(403, 'No tienes acceso a esta empresa.');
            }

            // Comparte el rol del usuario en esta empresa con las vistas
            view()->share('rolActual', $rol);
        }

        return $next($request);
    }
}
