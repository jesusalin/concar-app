@extends('layouts.app')
@section('title', 'Configuración contable')
@section('content')

    {{-- Volver --}}
    <a href="{{ route('empresas.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Empresas
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Configuración contable</h1>
        <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
    </div>

    <p class="text-slate-600 text-sm mb-6 max-w-2xl">
        Define qué cuenta del Plan de Cuentas se usa para cada concepto. El sistema usará esto para generar
        automáticamente los asientos contables de Compras y Ventas. Asegúrate de tener ya creado tu Plan de
        Cuentas antes de configurar esto.
    </p>

    <form action="{{ route('empresas.configuracion.update', $empresa) }}" method="POST"
          class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5 max-w-2xl">
        @csrf @method('PUT')

        @php
            $campos = [
                'cuenta_clientes_id'    => 'Cuenta de Clientes (CxC)',
                'cuenta_proveedores_id' => 'Cuenta de Proveedores (CxP)',
                'cuenta_igv_compras_id' => 'Cuenta IGV en Compras',
                'cuenta_igv_ventas_id'  => 'Cuenta IGV en Ventas',
                'cuenta_compras_id'     => 'Cuenta de Compras (gasto/costo)',
                'cuenta_ventas_id'      => 'Cuenta de Ventas (ingreso)',
            ];
            $hints = [
                'cuenta_clientes_id'    => 'ej: 1212',
                'cuenta_proveedores_id' => 'ej: 4212',
                'cuenta_igv_compras_id' => 'ej: 40111',
                'cuenta_igv_ventas_id'  => 'ej: 40111',
                'cuenta_compras_id'     => 'ej: 60',
                'cuenta_ventas_id'      => 'ej: 70 / 704',
            ];
        @endphp

        @foreach ($campos as $campo => $label)
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    {{ $label }}
                    <span class="text-slate-400 font-normal">— {{ $hints[$campo] }}</span>
                </label>
                <select name="{{ $campo }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400" required>
                    <option value="">— Seleccionar —</option>
                    @foreach ($cuentas as $cuenta)
                        <option value="{{ $cuenta->id }}" @selected($config->$campo == $cuenta->id)>
                            {{ $cuenta->codigo }} — {{ $cuenta->denominacion }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <div class="pt-2">
            <button class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Guardar configuración
            </button>
        </div>
    </form>

    @if ($cuentas->isEmpty())
        <div class="mt-4 flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-200 px-4 py-3 rounded-lg max-w-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            No tienes cuentas en tu Plan de Cuentas todavía.
            <a href="{{ route('empresas.plancuentas.create', $empresa) }}" class="underline font-medium">Crea cuentas primero</a>.
        </div>
    @endif

@endsection