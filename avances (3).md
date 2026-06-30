En tu archivo bootstrap/app.php (Laravel 11/12), dentro del método
->withMiddleware(function (Middleware $middleware) { ... }), agrega:

    $middleware->alias([
        'empresa.acceso' => \App\Http\Middleware\EnsureEmpresaAccess::class,
        'empresa.admin' => \App\Http\Middleware\RequiereRolAdministrador::class,
        'empresa.edicion' => \App\Http\Middleware\RequiereRolEdicion::class,
    ]);

Ejemplo completo de cómo debería quedar bootstrap/app.php:

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'empresa.acceso' => \App\Http\Middleware\EnsureEmpresaAccess::class,
            'empresa.admin' => \App\Http\Middleware\RequiereRolAdministrador::class,
            'empresa.edicion' => \App\Http\Middleware\RequiereRolEdicion::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

---

Si tu proyecto es Laravel 10 (con app/Http/Kernel.php), en vez de esto
agrega las 3 líneas dentro del array $middlewareAliases de ese archivo.
