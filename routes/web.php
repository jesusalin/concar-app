<?php
 
use App\Http\Controllers\AsientoController;
use App\Http\Controllers\ConfiguracionContableController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpresaUsuarioController;
use App\Http\Controllers\PlanCuentaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistroCompraController;
use App\Http\Controllers\RegistroVentaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TerceroController;
use Illuminate\Support\Facades\Route;
 
Route::get('/', function () {
    return redirect()->route('empresas.index');
});
 
// --- Rutas generadas por Breeze (perfil de usuario) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
 
// Rutas de autenticación (login/registro/logout) las agrega Breeze en auth.php
require __DIR__.'/auth.php';
 
// --- Rutas del sistema ConCar ---
Route::middleware('auth')->group(function () {
 
    Route::resource('empresas', EmpresaController::class)->except(['edit', 'update', 'show', 'destroy']);
    Route::delete('empresas/{empresa}', [EmpresaController::class, 'destroy'])
        ->middleware(['empresa.acceso', 'empresa.admin'])
        ->name('empresas.destroy');
 
    Route::prefix('empresas/{empresa}')->middleware('empresa.acceso')->group(function () {
 
        // Lectura: cualquier rol asignado a la empresa puede ver
        Route::resource('plancuentas', PlanCuentaController::class)
            ->only(['index'])->names('empresas.plancuentas');
        Route::resource('asientos', AsientoController::class)
            ->only(['index', 'show'])->names('empresas.asientos');
        Route::resource('terceros', TerceroController::class)
            ->only(['index'])->names('empresas.terceros');
        Route::resource('compras', RegistroCompraController::class)
            ->only(['index'])->names('empresas.compras');
        Route::resource('ventas', RegistroVentaController::class)
            ->only(['index'])->names('empresas.ventas');
 
        Route::get('reportes/libro-diario', [ReporteController::class, 'libroDiario'])->name('empresas.reportes.libro-diario');
        Route::get('reportes/libro-mayor', [ReporteController::class, 'libroMayor'])->name('empresas.reportes.libro-mayor');
        Route::get('reportes/balance', [ReporteController::class, 'balanceComprobacion'])->name('empresas.reportes.balance');
 
        // Escritura: solo administrador o contador
        Route::middleware('empresa.edicion')->group(function () {
            Route::resource('plancuentas', PlanCuentaController::class)
                ->only(['create', 'store', 'destroy'])->names('empresas.plancuentas');
            Route::resource('asientos', AsientoController::class)
                ->only(['create', 'store', 'destroy'])->names('empresas.asientos');
            Route::resource('terceros', TerceroController::class)
                ->only(['create', 'store', 'destroy'])->names('empresas.terceros');
            Route::resource('compras', RegistroCompraController::class)
                ->only(['create', 'store', 'destroy'])->names('empresas.compras');
            Route::resource('ventas', RegistroVentaController::class)
                ->only(['create', 'store', 'destroy'])->names('empresas.ventas');
        });
 
        // Solo administrador: configuración contable y gestión de usuarios
        Route::middleware('empresa.admin')->group(function () {
            Route::get('configuracion', [ConfiguracionContableController::class, 'edit'])->name('empresas.configuracion.edit');
            Route::put('configuracion', [ConfiguracionContableController::class, 'update'])->name('empresas.configuracion.update');
 
            Route::get('usuarios', [EmpresaUsuarioController::class, 'index'])->name('empresas.usuarios.index');
            Route::post('usuarios', [EmpresaUsuarioController::class, 'store'])->name('empresas.usuarios.store');
            Route::delete('usuarios/{empresaUsuario}', [EmpresaUsuarioController::class, 'destroy'])->name('empresas.usuarios.destroy');
        });
    });
});
 