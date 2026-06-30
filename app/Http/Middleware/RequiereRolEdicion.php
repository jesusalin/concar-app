<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequiereRolEdicion
{
    public function handle(Request $request, Closure $next): Response
    {
        $empresa = $request->route('empresa');

        if ($empresa instanceof Empresa) {
            $rol = $empresa->rolDe($request->user()->id);

            if (! in_array($rol, ['administrador', 'contador'])) {
                abort(403, 'Tu rol (asistente) solo tiene acceso de lectura.');
            }
        }

        return $next($request);
    }
}
