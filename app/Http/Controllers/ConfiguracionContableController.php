<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionContable;
use App\Models\Empresa;
use Illuminate\Http\Request;

class ConfiguracionContableController extends Controller
{
    public function edit(Empresa $empresa)
    {
        $config = $empresa->configuracionContable ?? new ConfiguracionContable(['empresa_id' => $empresa->id]);
        $cuentas = $empresa->planCuentas()->where('acepta_movimiento', true)->orderBy('codigo')->get();

        return view('configuracion.edit', compact('empresa', 'config', 'cuentas'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'cuenta_clientes_id' => 'required|exists:plan_cuentas,id',
            'cuenta_proveedores_id' => 'required|exists:plan_cuentas,id',
            'cuenta_igv_compras_id' => 'required|exists:plan_cuentas,id',
            'cuenta_igv_ventas_id' => 'required|exists:plan_cuentas,id',
            'cuenta_compras_id' => 'required|exists:plan_cuentas,id',
            'cuenta_ventas_id' => 'required|exists:plan_cuentas,id',
        ]);

        ConfiguracionContable::updateOrCreate(['empresa_id' => $empresa->id], $data);

        return redirect()->route('empresas.configuracion.edit', $empresa)
            ->with('ok', 'Configuración contable guardada. Ya puedes registrar compras y ventas.');
    }
}
