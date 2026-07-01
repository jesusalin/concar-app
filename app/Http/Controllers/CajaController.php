<?php

namespace App\Http\Controllers;

use App\Models\CuentaBancaria;
use App\Models\Empresa;
use App\Models\MovimientoCaja;
use App\Models\Asiento;
use App\Models\AsientoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    // ── Cuentas bancarias ──────────────────────────────────────────

    public function index(Empresa $empresa)
    {
        $cuentas = $empresa->cuentasBancarias()->orderBy('tipo')->orderBy('nombre')->get();
        return view('caja.index', compact('empresa', 'cuentas'));
    }

    public function createCuenta(Empresa $empresa)
    {
        $planCuentas = $empresa->planCuentas()->where('acepta_movimiento', true)->orderBy('codigo')->get();
        return view('caja.create_cuenta', compact('empresa', 'planCuentas'));
    }

    public function storeCuenta(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'tipo'           => 'required|in:caja,banco',
            'nombre'         => 'required|string|max:100',
            'banco'          => 'nullable|string|max:60',
            'numero_cuenta'  => 'nullable|string|max:30',
            'moneda'         => 'required|in:PEN,USD,EUR',
            'saldo_inicial'  => 'required|numeric|min:0',
            'plan_cuenta_id' => 'nullable|exists:plan_cuentas,id',
        ]);

        $cuenta = $empresa->cuentasBancarias()->create([
            ...$data,
            'saldo_actual' => $data['saldo_inicial'],
        ]);

        return redirect()->route('empresas.caja.index', $empresa)
            ->with('ok', "Cuenta \"{$cuenta->nombre}\" creada correctamente.");
    }

    public function destroyCuenta(Empresa $empresa, CuentaBancaria $cuenta)
    {
        $cuenta->delete();
        return redirect()->route('empresas.caja.index', $empresa)
            ->with('ok', 'Cuenta eliminada.');
    }

    // ── Movimientos ───────────────────────────────────────────────

    public function movimientos(Empresa $empresa, CuentaBancaria $cuenta)
    {
        $movimientos = $cuenta->movimientos()->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();
        return view('caja.movimientos', compact('empresa', 'cuenta', 'movimientos'));
    }

    public function createMovimiento(Empresa $empresa, CuentaBancaria $cuenta)
    {
        return view('caja.create_movimiento', compact('empresa', 'cuenta'));
    }

    public function storeMovimiento(Request $request, Empresa $empresa, CuentaBancaria $cuenta)
    {
        $data = $request->validate([
            'fecha'      => 'required|date',
            'tipo'       => 'required|in:ingreso,egreso',
            'concepto'   => 'required|string|max:200',
            'referencia' => 'nullable|string|max:60',
            'monto'      => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($data, $empresa, $cuenta) {
            // Calcular nuevo saldo
            $saldo = $cuenta->saldo_actual;
            $saldo += $data['tipo'] === 'ingreso' ? $data['monto'] : -$data['monto'];

            MovimientoCaja::create([
                'empresa_id'         => $empresa->id,
                'cuenta_bancaria_id' => $cuenta->id,
                'fecha'              => $data['fecha'],
                'tipo'               => $data['tipo'],
                'concepto'           => $data['concepto'],
                'referencia'         => $data['referencia'] ?? null,
                'monto'              => $data['monto'],
                'saldo_resultante'   => $saldo,
            ]);

            $cuenta->saldo_actual = $saldo;
            $cuenta->save();
        });

        return redirect()->route('empresas.caja.movimientos', [$empresa, $cuenta])
            ->with('ok', 'Movimiento registrado correctamente.');
    }

    public function destroyMovimiento(Empresa $empresa, CuentaBancaria $cuenta, MovimientoCaja $movimiento)
    {
        DB::transaction(function () use ($cuenta, $movimiento) {
            $movimiento->delete();
            $cuenta->recalcularSaldo();
        });

        return redirect()->route('empresas.caja.movimientos', [$empresa, $cuenta])
            ->with('ok', 'Movimiento eliminado y saldo recalculado.');
    }
}
