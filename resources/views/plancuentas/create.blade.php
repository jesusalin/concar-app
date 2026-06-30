@extends('layouts.app')
@section('title', 'Nueva cuenta')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Nueva cuenta — {{ $empresa->razon_social }}</h1>

    <form action="{{ route('empresas.plancuentas.store', $empresa) }}" method="POST" class="bg-white p-6 rounded shadow space-y-4 max-w-lg">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Código (PCGE)</label>
            <input type="text" name="codigo" class="w-full border rounded p-2" placeholder="Ej: 1011" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Denominación</label>
            <input type="text" name="denominacion" class="w-full border rounded p-2" placeholder="Ej: Caja" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Nivel</label>
            <select name="nivel" class="w-full border rounded p-2">
                <option value="elemento">Elemento</option>
                <option value="cuenta">Cuenta</option>
                <option value="subcuenta">Subcuenta</option>
                <option value="divisionaria" selected>Divisionaria</option>
                <option value="subdivisionaria">Subdivisionaria</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Cuenta padre (opcional)</label>
            <select name="cuenta_padre_id" class="w-full border rounded p-2">
                <option value="">— Ninguna —</option>
                @foreach ($padres as $padre)
                    <option value="{{ $padre->id }}">{{ $padre->codigo }} — {{ $padre->denominacion }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="acepta_movimiento" value="1" checked id="am">
            <label for="am" class="text-sm">Acepta movimientos (cuenta hoja, usable en asientos)</label>
        </div>
        <button class="bg-slate-800 text-white px-4 py-2 rounded">Guardar</button>
    </form>
@endsection
