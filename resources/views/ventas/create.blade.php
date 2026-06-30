@extends('layouts.app')
@section('title', 'Nueva venta')
@section('content')

    <a href="{{ route('empresas.ventas.index', $empresa) }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Ventas
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Nueva venta</h1>
        <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
    </div>

    <form action="{{ route('empresas.ventas.store', $empresa) }}" method="POST"
          class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5 max-w-2xl">
        @csrf

        {{-- Cliente --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Cliente</label>
            <select name="tercero_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400" required>
                <option value="">— Seleccionar cliente —</option>
                @foreach ($clientes as $c)
                    <option value="{{ $c->id }}" @selected(old('tercero_id')==$c->id)>
                        {{ $c->numero_documento }} — {{ $c->razon_social }}
                    </option>
                @endforeach
            </select>
            @if ($clientes->isEmpty())
                <p class="text-xs text-red-600 mt-1">
                    No tienes clientes registrados.
                    <a href="{{ route('empresas.terceros.create', $empresa) }}" class="underline">Crear uno</a>.
                </p>
            @endif
        </div>

        {{-- Comprobante --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de comprobante</label>
                <select name="tipo_comprobante" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="Factura"        @selected(old('tipo_comprobante','Factura')==='Factura')>Factura</option>
                    <option value="Boleta"         @selected(old('tipo_comprobante')==='Boleta')>Boleta</option>
                    <option value="Nota de Credito"@selected(old('tipo_comprobante')==='Nota de Credito')>Nota de Crédito</option>
                    <option value="Nota de Debito" @selected(old('tipo_comprobante')==='Nota de Debito')>Nota de Débito</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Serie</label>
                    <input type="text" name="serie" value="{{ old('serie') }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-slate-400"
                           placeholder="F001" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Número</label>
                    <input type="text" name="numero" value="{{ old('numero') }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-slate-400"
                           placeholder="00001" required>
                </div>
            </div>
        </div>

        {{-- Fechas --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de emisión</label>
                <input type="date" name="fecha_emision" value="{{ old('fecha_emision') }}"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de registro</label>
                <input type="date" name="fecha_registro" value="{{ old('fecha_registro', date('Y-m-d')) }}"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400" required>
            </div>
        </div>

        {{-- Montos --}}
        <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Importes</p>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Base imponible</label>
                    <input type="number" step="0.01" min="0" id="base" name="base_imponible"
                           value="{{ old('base_imponible') }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-slate-400"
                           placeholder="0.00" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">IGV (18%)</label>
                    <input type="number" step="0.01" min="0" id="igv" name="igv"
                           value="{{ old('igv') }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-slate-400"
                           placeholder="0.00" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Total</label>
                    <input type="text" id="total" readonly
                           class="w-full border border-slate-200 bg-slate-100 rounded-lg px-3 py-2 text-sm text-right font-semibold text-slate-700"
                           placeholder="0.00">
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Guardar y generar asiento
            </button>
            <a href="{{ route('empresas.ventas.index', $empresa) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
        </div>
    </form>

    <script>
        const base  = document.getElementById('base');
        const igv   = document.getElementById('igv');
        const total = document.getElementById('total');

        function recalcular() {
            const b = parseFloat(base.value) || 0;
            const i = parseFloat(igv.value)  || 0;
            total.value = (b + i).toFixed(2);
        }

        base.addEventListener('input', () => {
            const b = parseFloat(base.value) || 0;
            igv.value = (b * 0.18).toFixed(2);
            recalcular();
        });
        igv.addEventListener('input', recalcular);

        recalcular();
    </script>

@endsection