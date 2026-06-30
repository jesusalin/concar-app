<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    // Libro Diario: lista todos los asientos en orden cronológico con sus líneas
    public function libroDiario(Empresa $empresa)
    {
        $asientos = $empresa->asientos()
            ->with('detalles.planCuenta')
            ->orderBy('fecha')
            ->orderBy('numero')
            ->get();

        return view('reportes.libro-diario', compact('empresa', 'asientos'));
    }

    // Libro Mayor: agrupa todos los movimientos por cuenta contable, ordenados por fecha del asiento
    public function libroMayor(Empresa $empresa)
    {
        $cuentas = $empresa->planCuentas()
            ->where('acepta_movimiento', true)
            ->with(['detalles.asiento'])
            ->orderBy('codigo')
            ->get()
            ->map(function ($cuenta) {
                $cuenta->detalles = $cuenta->detalles
                    ->sortBy(fn ($d) => $d->asiento->fecha . $d->asiento->numero)
                    ->values();
                return $cuenta;
            })
            ->filter(fn ($c) => $c->detalles->isNotEmpty());

        return view('reportes.libro-mayor', compact('empresa', 'cuentas'));
    }

    // Balance de Comprobación: suma de debe/haber por cuenta y su saldo
    public function balanceComprobacion(Empresa $empresa)
    {
        $cuentas = $empresa->planCuentas()
            ->where('acepta_movimiento', true)
            ->orderBy('codigo')
            ->get()
            ->map(function ($cuenta) {
                $totalDebe = $cuenta->detalles()->sum('debe');
                $totalHaber = $cuenta->detalles()->sum('haber');
                $cuenta->total_debe = $totalDebe;
                $cuenta->total_haber = $totalHaber;
                $cuenta->saldo = $totalDebe - $totalHaber; // positivo = deudor, negativo = acreedor
                return $cuenta;
            })
            ->filter(fn ($c) => $c->total_debe > 0 || $c->total_haber > 0);

        $totalGeneralDebe = $cuentas->sum('total_debe');
        $totalGeneralHaber = $cuentas->sum('total_haber');

        return view('reportes.balance', compact('empresa', 'cuentas', 'totalGeneralDebe', 'totalGeneralHaber'));
    }
}
