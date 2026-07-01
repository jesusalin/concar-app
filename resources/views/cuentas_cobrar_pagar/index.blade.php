@extends('layouts.app')
@section('title', 'Cuentas por Cobrar / Pagar')
@section('content')

    <a href="{{ route('empresas.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Empresas
    </a>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Cuentas por Cobrar / Pagar</h1>
            <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('empresas.cxcp.historial', $empresa) }}"
               class="inline-flex items-center gap-1.5 text-sm border border-slate-300 text-slate-600 hover:bg-slate-50 px-3 py-2 rounded-lg transition-colors">
                Historial
            </a>
            <a href="{{ route('empresas.cxcp.create', $empresa) }}"
               class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo
            </a>
        </div>
    </div>

    {{-- Resumen --}}
    <div class="grid grid-cols-2 gap-4 mb-8 max-w-md">
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <p class="text-xs text-green-600 font-medium mb-1">Por Cobrar (pendiente)</p>
            <p class="text-2xl font-bold text-green-800">S/ {{ number_format($totalCobrar, 2) }}</p>
            <p class="text-xs text-green-600 mt-0.5">{{ $porCobrar->count() }} documentos</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <p class="text-xs text-red-600 font-medium mb-1">Por Pagar (pendiente)</p>
            <p class="text-2xl font-bold text-red-800">S/ {{ number_format($totalPagar, 2) }}</p>
            <p class="text-xs text-red-600 mt-0.5">{{ $porPagar->count() }} documentos</p>
        </div>
    </div>

    {{-- Por Cobrar --}}
    <h2 class="text-lg font-semibold text-slate-800 mb-3">Por Cobrar</h2>
    @if ($porCobrar->isEmpty())
        <p class="text-slate-400 text-sm mb-8">No hay cuentas por cobrar pendientes.</p>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            @include('cuentas_cobrar_pagar._tabla', ['items' => $porCobrar, 'tipo' => 'cobrar', 'empresa' => $empresa])
        </div>
    @endif

    {{-- Por Pagar --}}
    <h2 class="text-lg font-semibold text-slate-800 mb-3">Por Pagar</h2>
    @if ($porPagar->isEmpty())
        <p class="text-slate-400 text-sm">No hay cuentas por pagar pendientes.</p>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @include('cuentas_cobrar_pagar._tabla', ['items' => $porPagar, 'tipo' => 'pagar', 'empresa' => $empresa])
        </div>
    @endif

@endsection
