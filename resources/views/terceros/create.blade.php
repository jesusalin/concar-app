@extends('layouts.app')
@section('title', 'Nuevo tercero')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Nuevo cliente/proveedor — {{ $empresa->razon_social }}</h1>

    <form action="{{ route('empresas.terceros.store', $empresa) }}" method="POST" class="bg-white p-6 rounded shadow space-y-4 max-w-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tipo de documento</label>
                <select name="tipo_documento" class="w-full border rounded p-2">
                    <option value="RUC">RUC</option>
                    <option value="DNI">DNI</option>
                    <option value="CE">Carnet de Extranjería</option>
                    <option value="PASAPORTE">Pasaporte</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Número</label>
                <input type="text" name="numero_documento" class="w-full border rounded p-2" required>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Razón Social / Nombre</label>
            <input type="text" name="razon_social" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Tipo</label>
            <select name="tipo" class="w-full border rounded p-2">
                <option value="cliente">Cliente</option>
                <option value="proveedor">Proveedor</option>
                <option value="ambos" selected>Ambos</option>
            </select>
        </div>
        <button class="bg-slate-800 text-white px-4 py-2 rounded">Guardar</button>
    </form>
@endsection
