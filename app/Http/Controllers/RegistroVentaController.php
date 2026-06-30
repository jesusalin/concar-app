<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\AsientoDetalle;
use App\Models\Empresa;
use App\Models\RegistroVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistroVentaController extends Controller
{
    public function index(Empresa $empresa)
    {
        $ventas = $empresa->registroVentas()->with('tercero')->orderBy('fecha_emision', 'desc')->get();
        return view('ventas.index', compact('empresa', 'ventas'));
    }

    public function create(Empresa $empresa)
    {
        $config = $empresa->configuracionContable;
        if (!$config || !$config->estaCompleta()) {
            return redirect()->route('empresas.configuracion.edit', $empresa)
                ->with('ok', 'Antes de registrar ventas, configura el mapeo de cuentas contables.');
        }

        $clientes = $empresa->terceros()->whereIn('tipo', ['cliente', 'ambos'])->orderBy('razon_social')->get();
        return view('ventas.create', compact('empresa', 'clientes'));
    }

    public function store(Request $request, Empresa $empresa)
    {
        $config = $empresa->configuracionContable;
        if (!$config || !$config->estaCompleta()) {
            throw ValidationException::withMessages(['config' => 'La empresa no tiene configuración contable completa.']);
        }

        $data = $request->validate([
            'tercero_id' => 'required|exists:terceros,id',
            'tipo_comprobante' => 'required|in:Factura,Boleta,Nota de Credito,Nota de Debito',
            'serie' => 'required|string|max:10',
            'numero' => 'required|string|max:15',
            'fecha_emision' => 'required|date',
            'fecha_registro' => 'required|date',
            'base_imponible' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
        ]);

        $total = round($data['base_imponible'] + $data['igv'], 2);

        DB::transaction(function () use ($data, $empresa, $config, $total) {
            $correlativo = $empresa->asientos()->where('tipo', 'ventas')->count() + 1;
            $numeroAsiento = 'VTA-' . str_pad($correlativo, 5, '0', STR_PAD_LEFT);

            $asiento = Asiento::create([
                'empresa_id' => $empresa->id,
                'numero' => $numeroAsiento,
                'fecha' => $data['fecha_registro'],
                'tipo' => 'ventas',
                'glosa' => "Venta {$data['tipo_comprobante']} {$data['serie']}-{$data['numero']}",
                'total_debe' => $total,
                'total_haber' => $total,
            ]);

            // Debe: cuenta de clientes (total, lo que nos deben)
            AsientoDetalle::create([
                'asiento_id' => $asiento->id,
                'plan_cuenta_id' => $config->cuenta_clientes_id,
                'glosa' => 'Por cobrar',
                'debe' => $total,
                'haber' => 0,
            ]);

            // Haber: cuenta de ventas (base) + IGV de ventas
            AsientoDetalle::create([
                'asiento_id' => $asiento->id,
                'plan_cuenta_id' => $config->cuenta_ventas_id,
                'glosa' => 'Base imponible',
                'debe' => 0,
                'haber' => $data['base_imponible'],
            ]);

            if ($data['igv'] > 0) {
                AsientoDetalle::create([
                    'asiento_id' => $asiento->id,
                    'plan_cuenta_id' => $config->cuenta_igv_ventas_id,
                    'glosa' => 'IGV',
                    'debe' => 0,
                    'haber' => $data['igv'],
                ]);
            }

            RegistroVenta::create([
                'empresa_id' => $empresa->id,
                'tercero_id' => $data['tercero_id'],
                'asiento_id' => $asiento->id,
                'tipo_comprobante' => $data['tipo_comprobante'],
                'serie' => $data['serie'],
                'numero' => $data['numero'],
                'fecha_emision' => $data['fecha_emision'],
                'fecha_registro' => $data['fecha_registro'],
                'base_imponible' => $data['base_imponible'],
                'igv' => $data['igv'],
                'total' => $total,
            ]);
        });

        return redirect()->route('empresas.ventas.index', $empresa)
            ->with('ok', 'Venta registrada y asiento contable generado automáticamente.');
    }

    public function destroy(Empresa $empresa, RegistroVenta $venta)
    {
        if ($venta->asiento_id) {
            Asiento::destroy($venta->asiento_id);
        }
        $venta->delete();
        return redirect()->route('empresas.ventas.index', $empresa)->with('ok', 'Venta eliminada.');
    }
}
