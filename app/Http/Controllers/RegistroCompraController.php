<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\AsientoDetalle;
use App\Models\Empresa;
use App\Models\RegistroCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistroCompraController extends Controller
{
    public function index(Empresa $empresa)
    {
        $compras = $empresa->registroCompras()->with('tercero')->orderBy('fecha_emision', 'desc')->get();
        return view('compras.index', compact('empresa', 'compras'));
    }

    public function create(Empresa $empresa)
    {
        $config = $empresa->configuracionContable;
        if (!$config || !$config->estaCompleta()) {
            return redirect()->route('empresas.configuracion.edit', $empresa)
                ->with('ok', 'Antes de registrar compras, configura el mapeo de cuentas contables.');
        }

        $proveedores = $empresa->terceros()->whereIn('tipo', ['proveedor', 'ambos'])->orderBy('razon_social')->get();
        return view('compras.create', compact('empresa', 'proveedores'));
    }

    public function store(Request $request, Empresa $empresa)
    {
        $config = $empresa->configuracionContable;
        if (!$config || !$config->estaCompleta()) {
            throw ValidationException::withMessages(['config' => 'La empresa no tiene configuración contable completa.']);
        }

        $data = $request->validate([
            'tercero_id' => 'required|exists:terceros,id',
            'tipo_comprobante' => 'required|in:Factura,Boleta,Recibo por Honorarios,Nota de Credito,Nota de Debito',
            'serie' => 'required|string|max:10',
            'numero' => 'required|string|max:15',
            'fecha_emision' => 'required|date',
            'fecha_registro' => 'required|date',
            'base_imponible' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
        ]);

        $total = round($data['base_imponible'] + $data['igv'], 2);

        DB::transaction(function () use ($data, $empresa, $config, $total) {
            // Genera el número de asiento correlativo para compras
            $correlativo = $empresa->asientos()->where('tipo', 'compras')->count() + 1;
            $numeroAsiento = 'COM-' . str_pad($correlativo, 5, '0', STR_PAD_LEFT);

            $asiento = Asiento::create([
                'empresa_id' => $empresa->id,
                'numero' => $numeroAsiento,
                'fecha' => $data['fecha_registro'],
                'tipo' => 'compras',
                'glosa' => "Compra {$data['tipo_comprobante']} {$data['serie']}-{$data['numero']}",
                'total_debe' => $total,
                'total_haber' => $total,
            ]);

            // Debe: cuenta de compras (base) + IGV de compras
            AsientoDetalle::create([
                'asiento_id' => $asiento->id,
                'plan_cuenta_id' => $config->cuenta_compras_id,
                'glosa' => 'Base imponible',
                'debe' => $data['base_imponible'],
                'haber' => 0,
            ]);

            if ($data['igv'] > 0) {
                AsientoDetalle::create([
                    'asiento_id' => $asiento->id,
                    'plan_cuenta_id' => $config->cuenta_igv_compras_id,
                    'glosa' => 'IGV',
                    'debe' => $data['igv'],
                    'haber' => 0,
                ]);
            }

            // Haber: cuenta de proveedores (total)
            AsientoDetalle::create([
                'asiento_id' => $asiento->id,
                'plan_cuenta_id' => $config->cuenta_proveedores_id,
                'glosa' => 'Por pagar',
                'debe' => 0,
                'haber' => $total,
            ]);

            RegistroCompra::create([
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

        return redirect()->route('empresas.compras.index', $empresa)
            ->with('ok', 'Compra registrada y asiento contable generado automáticamente.');
    }

    public function destroy(Empresa $empresa, RegistroCompra $compra)
    {
        if ($compra->asiento_id) {
            Asiento::destroy($compra->asiento_id); // borra el asiento y sus líneas en cascada
        }
        $compra->delete();
        return redirect()->route('empresas.compras.index', $empresa)->with('ok', 'Compra eliminada.');
    }
}
