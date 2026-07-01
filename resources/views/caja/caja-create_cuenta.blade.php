@extends('layouts.app')
@section('title', 'Nueva cuenta')
@section('content')

    <a href="{{ route('empresas.caja.index', $empresa) }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Caja y Bancos
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Nueva cuenta</h1>
        <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
    </div>

    <form action="{{ route('empresas.caja.store-cuenta', $empresa) }}" method="POST"
          class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5 max-w-lg">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                <select name="tipo" id="tipo"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="banco" @selected(old('tipo','banco')==='banco')>Banco</option>
                    <option value="caja"  @selected(old('tipo')==='caja')>Caja</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Moneda</label>
                <select name="moneda" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="PEN" @selected(old('moneda','PEN')==='PEN')>PEN — Soles</option>
                    <option value="USD" @selected(old('moneda')==='USD')>USD — Dólares</option>
                    <option value="EUR" @selected(old('moneda')==='EUR')>EUR — Euros</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400"
                   placeholder="Ej: BCP Cuenta Corriente" required>
        </div>

        <div id="campos-banco">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Banco</label>
                    <input type="text" name="banco" value="{{ old('banco') }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400"
                           placeholder="Ej: BCP, BBVA, Interbank">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Número de cuenta</label>
                    <input type="text" name="numero_cuenta" value="{{ old('numero_cuenta') }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-slate-400"
                           placeholder="000-000000-0-00">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Saldo inicial</label>
            <input type="number" step="0.01" min="0" name="saldo_inicial" value="{{ old('saldo_inicial', '0.00') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-slate-400"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Cuenta contable vinculada <span class="text-slate-400 font-normal">(opcional)</span>
            </label>
            <select name="plan_cuenta_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                <option value="">— No vincular —</option>
                @foreach ($planCuentas as $pc)
                    <option value="{{ $pc->id }}" @selected(old('plan_cuenta_id')==$pc->id)>
                        {{ $pc->codigo }} — {{ $pc->denominacion }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Guardar cuenta
            </button>
            <a href="{{ route('empresas.caja.index', $empresa) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
        </div>
    </form>

    <script>
        const tipo = document.getElementById('tipo');
        const camposBanco = document.getElementById('campos-banco');
        tipo.addEventListener('change', () => {
            camposBanco.style.display = tipo.value === 'banco' ? 'block' : 'none';
        });
        camposBanco.style.display = tipo.value === 'banco' ? 'block' : 'none';
    </script>

@endsection
