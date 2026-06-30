@extends('layouts.app')
@section('title', 'Nueva empresa')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Nueva empresa</h1>

    <form action="{{ route('empresas.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4 max-w-lg">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">RUC</label>
            <input type="text" name="ruc" maxlength="11" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Razón Social</label>
            <input type="text" name="razon_social" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Moneda</label>
            <select name="moneda" class="w-full border rounded p-2">
                <option value="PEN">Soles (PEN)</option>
                <option value="USD">Dólares (USD)</option>
                <option value="EUR">Euros (EUR)</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Fecha de inicio de actividades</label>
            <input type="date" name="fecha_inicio_actividades" class="w-full border rounded p-2">
        </div>
        <button class="bg-slate-800 text-white px-4 py-2 rounded">Guardar</button>
    </form>
@endsection
