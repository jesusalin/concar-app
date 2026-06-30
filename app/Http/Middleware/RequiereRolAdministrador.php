<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequiereRolAdministrador
{
    public function handle(Request $request, Closure $next): Response
    {
        $empresa = $request->route('empresa');

        if ($empresa instanceof Empresa) {
            $rol = $empresa->rolDe($request->user()->id);

            if ($rol !== 'administrador') {
                abort(403, 'Solo un administrador de esta empresa puede hacer esto.');
            }
        }

        return $next($request);
    }
}
