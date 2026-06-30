<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\PlanCuenta;
use Illuminate\Http\Request;

class PlanCuentaController extends Controller
{
    public function index(Empresa $empresa)
    {
        $cuentas = $empresa->planCuentas()->orderBy('codigo')->get();
        return view('plancuentas.index', compact('empresa', 'cuentas'));
    }

    public function create(Empresa $empresa)
    {
        $padres = $empresa->planCuentas()->orderBy('codigo')->get();
        return view('plancuentas.create', compact('empresa', 'padres'));
    }

    public function store(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:20',
            'denominacion' => 'required|string|max:255',
            'nivel' => 'required|in:elemento,cuenta,subcuenta,divisionaria,subdivisionaria',
            'cuenta_padre_id' => 'nullable|exists:plan_cuentas,id',
            'acepta_movimiento' => 'sometimes|boolean',
        ]);

        $data['empresa_id'] = $empresa->id;
        $data['acepta_movimiento'] = $request->boolean('acepta_movimiento');

        PlanCuenta::create($data);

        return redirect()->route('empresas.plancuentas.index', $empresa)
            ->with('ok', 'Cuenta agregada al plan contable.');
    }

    public function destroy(Empresa $empresa, PlanCuenta $plancuenta)
    {
        $plancuenta->delete();
        return redirect()->route('empresas.plancuentas.index', $empresa)
            ->with('ok', 'Cuenta eliminada.');
    }
}
