@extends('layouts.app')
@section('title', 'Caja y Bancos')
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
            <h1 class="text-2xl font-bold text-slate-800">Caja y Bancos</h1>
            <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
        </div>
        <a href="{{ route('empresas.caja.create-cuenta', $empresa) }}"
           class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva cuenta
        </a>
    </div>

    @if ($cuentas->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <p class="font-medium">No tienes cuentas registradas</p>
            <p class="text-sm mt-1">Agrega tu primera caja o cuenta bancaria</p>
        </div>
    @else
        {{-- Resumen total --}}
        @php
            $totalPEN = $cuentas->where('moneda','PEN')->sum('saldo_actual');
            $totalUSD = $cuentas->where('moneda','USD')->sum('saldo_actual');
        @endphp
        <div class="grid grid-cols-2 gap-4 mb-6 max-w-sm">
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-xs text-slate-500 mb-1">Total PEN</p>
                <p class="text-xl font-bold text-slate-800">S/ {{ number_format($totalPEN, 2) }}</p>
            </div>
            @if ($totalUSD > 0)
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-xs text-slate-500 mb-1">Total USD</p>
                <p class="text-xl font-bold text-slate-800">$ {{ number_format($totalUSD, 2) }}</p>
            </div>
            @endif
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($cuentas as $cuenta)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if ($cuenta->tipo === 'caja')
                                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-slate-800 text-sm">{{ $cuenta->nombre }}</p>
                                @if ($cuenta->banco)
                                    <p class="text-xs text-slate-400">{{ $cuenta->banco }}
                                        @if ($cuenta->numero_cuenta) · {{ $cuenta->numero_cuenta }} @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                        <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $cuenta->moneda }}</span>
                    </div>
                    <div class="px-5 py-4">
                        <p class="text-2xl font-bold {{ $cuenta->saldo_actual >= 0 ? 'text-slate-800' : 'text-red-600' }}">
                            {{ number_format($cuenta->saldo_actual, 2) }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">Saldo actual</p>

                        <div class="flex items-center gap-2 mt-4">
                            <a href="{{ route('empresas.caja.movimientos', [$empresa, $cuenta]) }}"
                               class="flex-1 text-center text-xs bg-slate-800 hover:bg-slate-700 text-white px-3 py-2 rounded-lg transition-colors font-medium">
                                Ver movimientos
                            </a>
                            <form action="{{ route('empresas.caja.destroy-cuenta', [$empresa, $cuenta]) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar la cuenta {{ $cuenta->nombre }} y todos sus movimientos?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors border border-red-200">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
