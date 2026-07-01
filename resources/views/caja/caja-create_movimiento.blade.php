@extends('layouts.app')
@section('title', 'Nuevo movimiento')
@section('content')

    <a href="{{ route('empresas.caja.movimientos', [$empresa, $cuenta]) }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Movimientos
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Nuevo movimiento</h1>
        <p class="text-slate-500 text-sm mt-0.5">{{ $cuenta->nombre }} — {{ $empresa->razon_social }}</p>
    </div>

    <div class="bg-slate-50 rounded-lg border border-slate-200 px-4 py-3 mb-5 max-w-lg flex items-center justify-between">
        <span class="text-sm text-slate-600">Saldo actual</span>
        <span class="font-bold text-slate-800">{{ $cuenta->moneda }} {{ number_format($cuenta->saldo_actual, 2) }}</span>
    </div>

    <form action="{{ route('empresas.caja.store-movimiento', [$empresa, $cuenta]) }}" method="POST"
          class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5 max-w-lg">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha</label>
                <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                <select name="tipo" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="ingreso" @selected(old('tipo','ingreso')==='ingreso')>Ingreso</option>
                    <option value="egreso"  @selected(old('tipo')==='egreso')>Egreso</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Concepto</label>
            <input type="text" name="concepto" value="{{ old('concepto') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400"
                   placeholder="Ej: Cobro factura F001-00001" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Referencia <span class="text-slate-400 font-normal">(opcional)</span>
            </label>
            <input type="text" name="referencia" value="{{ old('referencia') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400"
                   placeholder="Nro. cheque, transferencia, etc.">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Monto</label>
            <input type="number" step="0.01" min="0.01" name="monto" value="{{ old('monto') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-slate-400"
                   placeholder="0.00" required>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Guardar movimiento
            </button>
            <a href="{{ route('empresas.caja.movimientos', [$empresa, $cuenta]) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
        </div>
    </form>

@endsection
