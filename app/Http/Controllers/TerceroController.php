<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Tercero;
use Illuminate\Http\Request;

class TerceroController extends Controller
{
    public function index(Empresa $empresa)
    {
        $terceros = $empresa->terceros()->orderBy('razon_social')->get();
        return view('terceros.index', compact('empresa', 'terceros'));
    }

    public function create(Empresa $empresa)
    {
        return view('terceros.create', compact('empresa'));
    }

    public function store(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'tipo_documento' => 'required|in:RUC,DNI,CE,PASAPORTE',
            'numero_documento' => 'required|string|max:15',
            'razon_social' => 'required|string|max:255',
            'tipo' => 'required|in:cliente,proveedor,ambos',
        ]);

        $data['empresa_id'] = $empresa->id;
        Tercero::create($data);

        return redirect()->route('empresas.terceros.index', $empresa)->with('ok', 'Tercero registrado.');
    }

    public function destroy(Empresa $empresa, Tercero $tercero)
    {
        $tercero->delete();
        return redirect()->route('empresas.terceros.index', $empresa)->with('ok', 'Tercero eliminado.');
    }
}
