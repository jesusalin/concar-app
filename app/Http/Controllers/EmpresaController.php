<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = auth()->user()->empresas()->orderBy('razon_social')->get();
        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ruc' => 'required|digits:11|unique:empresas,ruc',
            'razon_social' => 'required|string|max:255',
            'moneda' => 'required|in:PEN,USD,EUR',
            'fecha_inicio_actividades' => 'nullable|date',
        ]);

        $empresa = Empresa::create($data);

        // Quien crea la empresa queda como administrador de ella
        $empresa->usuarios()->attach(auth()->id(), ['rol' => 'administrador']);

        return redirect()->route('empresas.index')->with('ok', 'Empresa creada correctamente.');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('empresas.index')->with('ok', 'Empresa eliminada.');
    }
}
