@extends('layouts.app')
@section('title', 'Nueva compra')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Nueva compra — {{ $empresa->razon_social }}</h1>

    <form action="{{ route('empresas.compras.store', $empresa) }}" method="POST" class="bg-white p-6 rounded shadow space-y-4 max-w-xl">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Proveedor</label>
            <select name="tercero_id" class="w-full border rounded p-2" required>
                <option value="">— Seleccionar —</option>
                @foreach ($proveedores as $p)
                    <option value="{{ $p->id }}">{{ $p->numero_documento }} — {{ $p->razon_social }}</option>
                @endforeach
            </select>
            @if ($proveedores->isEmpty())
                <p class="text-sm text-red-600 mt-1">No tienes proveedores registrados. <a href="{{ route('empresas.terceros.create', $empresa) }}" class="underline">Crear uno</a>.</p>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tipo de comprobante</label>
                <select name="tipo_comprobante" class="w-full border rounded p-2">
                    <option value="Factura">Factura</option>
                    <option value="Boleta">Boleta</option>
                    <option value="Recibo por Honorarios">Recibo por Honorarios</option>
                    <option value="Nota de Credito">Nota de Crédito</option>
                    <option value="Nota de Debito">Nota de Débito</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-sm font-medium mb-1">Serie</label>
                    <input type="text" name="serie" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Número</label>
                    <input type="text" name="numero" class="w-full border rounded p-2" required>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Fecha de emisión</label>
                <input type="date" name="fecha_emision" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Fecha de registro</label>
                <input type="date" name="fecha_registro" class="w-full border rounded p-2" required>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Base imponible</label>
                <input type="number" step="0.01" id="base" name="base_imponible" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">IGV (18%)</label>
                <input type="number" step="0.01" id="igv" name="igv" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Total</label>
                <input type="text" id="total" class="w-full border rounded p-2 bg-slate-100" disabled>
            </div>
        </div>

        <button class="bg-slate-800 text-white px-4 py-2 rounded">Guardar y generar asiento</button>
    </form>

    <script>
        const base = document.getElementById('base');
        const igv = document.getElementById('igv');
        const total = document.getElementById('total');

        base.addEventListener('input', () => {
            const b = parseFloat(base.value) || 0;
            igv.value = (b * 0.18).toFixed(2);
            recalcularTotal();
        });
        igv.addEventListener('input', recalcularTotal);

        function recalcularTotal() {
            const b = parseFloat(base.value) || 0;
            const i = parseFloat(igv.value) || 0;
            total.value = (b + i).toFixed(2);
        }
    </script>
@endsection
