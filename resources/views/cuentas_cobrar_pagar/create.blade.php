@extends('layouts.app')
@section('title', 'Nueva cuenta por cobrar/pagar')
@section('content')
    <a href="{{ route('empresas.cxcp.index', $empresa) }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver
    </a>

    <h1 class="text-2xl font-bold text-slate-900 mb-6">Nueva cuenta — {{ $empresa->razon_social }}</h1>

    <form action="{{ route('empresas.cxcp.store', $empresa) }}" method="POST"
          class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 space-y-4 max-w-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                <select name="tipo" class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-slate-800">
                    <option value="cobrar">Por Cobrar (cliente nos debe)</option>
                    <option value="pagar">Por Pagar (le debemos al proveedor)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tercero</label>
                <select name="tercero_id" class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-slate-800" required>
                    <option value="">— Seleccionar —</option>
                    @foreach ($terceros as $t)
                        <option value="{{ $t->id }}">{{ $t->razon_social }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Origen (opcional)</label>
            <input type="text" name="origen" placeholder="Ej: Factura F001-0023"
                class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-slate-800">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de emisión</label>
                <input type="date" name="fecha_emision" class="w-full border rounded-lg p-2.5" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de vencimiento</label>
                <input type="date" name="fecha_vencimiento" class="w-full border rounded-lg p-2.5">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Monto original</label>
            <input type="number" step="0.01" name="monto_original"
                class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-slate-800" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Notas (opcional)</label>
            <textarea name="notas" rows="2"
                class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-slate-800"></textarea>
        </div>
        <button class="w-full bg-slate-800 text-white rounded-lg py-2.5 font-medium hover:bg-slate-700 transition">
            Guardar
        </button>
    </form>
@endsection
