<?php

namespace App\Http\Controllers;

use App\Models\CuentaPorCobrarPagar;
use App\Models\Empresa;
use Illuminate\Http\Request;

class CuentasPorCobrarPagarController extends Controller
{
    public function index(Empresa $empresa)
    {
        $porCobrar = $empresa->cuentasPorCobrarPagar()
            ->with('tercero')
            ->where('tipo', 'cobrar')
            ->whereIn('estado', ['pendiente', 'parcial'])
            ->orderBy('fecha_vencimiento')
            ->get();

        $porPagar = $empresa->cuentasPorCobrarPagar()
            ->with('tercero')
            ->where('tipo', 'pagar')
            ->whereIn('estado', ['pendiente', 'parcial'])
            ->orderBy('fecha_vencimiento')
            ->get();

        $totalCobrar = $porCobrar->sum('saldo_pendiente');
        $totalPagar  = $porPagar->sum('saldo_pendiente');

        return view('cuentas_cobrar_pagar.index', compact(
            'empresa', 'porCobrar', 'porPagar', 'totalCobrar', 'totalPagar'
        ));
    }

    public function create(Empresa $empresa)
    {
        $terceros = $empresa->terceros()->orderBy('razon_social')->get();
        return view('cuentas_cobrar_pagar.create', compact('empresa', 'terceros'));
    }

    public function store(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'tipo'               => 'required|in:cobrar,pagar',
            'tercero_id'         => 'required|exists:terceros,id',
            'origen'             => 'nullable|string|max:100',
            'fecha_emision'      => 'required|date',
            'fecha_vencimiento'  => 'nullable|date|after_or_equal:fecha_emision',
            'monto_original'     => 'required|numeric|min:0.01',
            'notas'              => 'nullable|string|max:200',
        ]);

        $empresa->cuentasPorCobrarPagar()->create([
            ...$data,
            'saldo_pendiente' => $data['monto_original'],
            'monto_pagado'    => 0,
            'estado'          => 'pendiente',
        ]);

        return redirect()->route('empresas.cxcp.index', $empresa)
            ->with('ok', 'Cuenta por ' . ($data['tipo'] === 'cobrar' ? 'cobrar' : 'pagar') . ' registrada.');
    }

    public function registrarPago(Request $request, Empresa $empresa, CuentaPorCobrarPagar $cuenta)
    {
        $data = $request->validate([
            'monto' => 'required|numeric|min:0.01|max:' . $cuenta->saldo_pendiente,
        ]);

        $cuenta->registrarPago((float) $data['monto']);

        return redirect()->route('empresas.cxcp.index', $empresa)
            ->with('ok', 'Pago de ' . number_format($data['monto'], 2) . ' registrado.');
    }

    public function historial(Empresa $empresa)
    {
        $canceladas = $empresa->cuentasPorCobrarPagar()
            ->with('tercero')
            ->where('estado', 'cancelado')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('cuentas_cobrar_pagar.historial', compact('empresa', 'canceladas'));
    }

    public function destroy(Empresa $empresa, CuentaPorCobrarPagar $cuenta)
    {
        $cuenta->delete();
        return redirect()->route('empresas.cxcp.index', $empresa)
            ->with('ok', 'Cuenta eliminada.');
    }
}
