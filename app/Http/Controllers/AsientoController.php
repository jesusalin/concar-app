<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\AsientoDetalle;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AsientoController extends Controller
{
    public function index(Empresa $empresa)
    {
        $asientos = $empresa->asientos()->orderBy('fecha')->orderBy('numero')->get();
        return view('asientos.index', compact('empresa', 'asientos'));
    }

    public function create(Empresa $empresa)
    {
        $cuentas = $empresa->planCuentas()->where('acepta_movimiento', true)->orderBy('codigo')->get();
        return view('asientos.create', compact('empresa', 'cuentas'));
    }

    public function store(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'numero' => 'required|string|max:20',
            'fecha' => 'required|date',
            'tipo' => 'required|in:apertura,compras,ventas,honorarios,planillas,ajuste,cierre',
            'glosa' => 'nullable|string|max:255',
            'lineas' => 'required|array|min:2',
            'lineas.*.plan_cuenta_id' => 'required|exists:plan_cuentas,id',
            'lineas.*.glosa' => 'nullable|string|max:255',
            'lineas.*.debe' => 'nullable|numeric|min:0',
            'lineas.*.haber' => 'nullable|numeric|min:0',
        ]);

        $totalDebe = 0;
        $totalHaber = 0;
        foreach ($data['lineas'] as $linea) {
            $totalDebe += (float) ($linea['debe'] ?? 0);
            $totalHaber += (float) ($linea['haber'] ?? 0);
        }

        // Regla de oro de la partida doble: el asiento debe cuadrar.
        if (round($totalDebe, 2) !== round($totalHaber, 2)) {
            throw ValidationException::withMessages([
                'lineas' => "El asiento no cuadra. Debe: {$totalDebe} / Haber: {$totalHaber}. Deben ser iguales.",
            ]);
        }

        if ($totalDebe == 0) {
            throw ValidationException::withMessages([
                'lineas' => 'El asiento no puede tener montos en cero.',
            ]);
        }

        DB::transaction(function () use ($data, $empresa, $totalDebe, $totalHaber) {
            $asiento = Asiento::create([
                'empresa_id' => $empresa->id,
                'numero' => $data['numero'],
                'fecha' => $data['fecha'],
                'tipo' => $data['tipo'],
                'glosa' => $data['glosa'] ?? null,
                'total_debe' => $totalDebe,
                'total_haber' => $totalHaber,
            ]);

            foreach ($data['lineas'] as $linea) {
                if (($linea['debe'] ?? 0) == 0 && ($linea['haber'] ?? 0) == 0) {
                    continue; // ignora líneas vacías
                }
                AsientoDetalle::create([
                    'asiento_id' => $asiento->id,
                    'plan_cuenta_id' => $linea['plan_cuenta_id'],
                    'glosa' => $linea['glosa'] ?? null,
                    'debe' => $linea['debe'] ?? 0,
                    'haber' => $linea['haber'] ?? 0,
                ]);
            }
        });

        return redirect()->route('empresas.asientos.index', $empresa)
            ->with('ok', 'Asiento contable registrado correctamente.');
    }

    public function show(Empresa $empresa, Asiento $asiento)
    {
        $asiento->load('detalles.planCuenta');
        return view('asientos.show', compact('empresa', 'asiento'));
    }

    public function destroy(Empresa $empresa, Asiento $asiento)
    {
        $asiento->delete(); // las líneas se borran en cascada (ver migración)
        return redirect()->route('empresas.asientos.index', $empresa)
            ->with('ok', 'Asiento eliminado.');
    }
}
