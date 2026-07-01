<?php

use App\Http\Controllers\AsientoController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ConfiguracionContableController;
use App\Http\Controllers\CuentasPorCobrarPagarController;
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

        // ── Lectura: cualquier rol ────────────────────────────────────────────
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

        // Caja y Bancos (lectura)
        Route::get('caja', [CajaController::class, 'index'])->name('empresas.caja.index');
        Route::get('caja/{cuenta}/movimientos', [CajaController::class, 'movimientos'])->name('empresas.caja.movimientos');

        // CxC / CxP (lectura)
        Route::get('cxcp', [CuentasPorCobrarPagarController::class, 'index'])->name('empresas.cxcp.index');
        Route::get('cxcp/historial', [CuentasPorCobrarPagarController::class, 'historial'])->name('empresas.cxcp.historial');

        // ── Escritura: solo administrador o contador ──────────────────────────
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

            // Caja y Bancos (escritura)
            Route::get('caja/create', [CajaController::class, 'createCuenta'])->name('empresas.caja.create-cuenta');
            Route::post('caja', [CajaController::class, 'storeCuenta'])->name('empresas.caja.store-cuenta');
            Route::delete('caja/{cuenta}', [CajaController::class, 'destroyCuenta'])->name('empresas.caja.destroy-cuenta');
            Route::get('caja/{cuenta}/movimientos/create', [CajaController::class, 'createMovimiento'])->name('empresas.caja.create-movimiento');
            Route::post('caja/{cuenta}/movimientos', [CajaController::class, 'storeMovimiento'])->name('empresas.caja.store-movimiento');
            Route::delete('caja/{cuenta}/movimientos/{movimiento}', [CajaController::class, 'destroyMovimiento'])->name('empresas.caja.destroy-movimiento');

            // CxC / CxP (escritura)
            Route::get('cxcp/create', [CuentasPorCobrarPagarController::class, 'create'])->name('empresas.cxcp.create');
            Route::post('cxcp', [CuentasPorCobrarPagarController::class, 'store'])->name('empresas.cxcp.store');
            Route::post('cxcp/{cuenta}/pago', [CuentasPorCobrarPagarController::class, 'registrarPago'])->name('empresas.cxcp.pago');
            Route::delete('cxcp/{cuenta}', [CuentasPorCobrarPagarController::class, 'destroy'])->name('empresas.cxcp.destroy');
        });

        // ── Solo administrador ────────────────────────────────────────────────
        Route::middleware('empresa.admin')->group(function () {
            Route::get('configuracion', [ConfiguracionContableController::class, 'edit'])->name('empresas.configuracion.edit');
            Route::put('configuracion', [ConfiguracionContableController::class, 'update'])->name('empresas.configuracion.update');

            Route::get('usuarios', [EmpresaUsuarioController::class, 'index'])->name('empresas.usuarios.index');
            Route::post('usuarios', [EmpresaUsuarioController::class, 'store'])->name('empresas.usuarios.store');
            Route::delete('usuarios/{empresaUsuario}', [EmpresaUsuarioController::class, 'destroy'])->name('empresas.usuarios.destroy');
        });
    });
});
