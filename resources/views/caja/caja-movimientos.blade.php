@extends('layouts.app')
@section('title', 'Movimientos — ' . $cuenta->nombre)
@section('content')

    <a href="{{ route('empresas.caja.index', $empresa) }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Caja y Bancos
    </a>

    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $cuenta->nombre }}</h1>
            <p class="text-slate-500 text-sm mt-0.5">
                {{ $empresa->razon_social }}
                @if ($cuenta->banco) · {{ $cuenta->banco }} @endif
                @if ($cuenta->numero_cuenta) · {{ $cuenta->numero_cuenta }} @endif
            </p>
        </div>
        <div class="text-right">
            <p class="text-xs text-slate-400 mb-0.5">Saldo actual</p>
            <p class="text-2xl font-bold {{ $cuenta->saldo_actual >= 0 ? 'text-slate-800' : 'text-red-600' }}">
                {{ $cuenta->moneda }} {{ number_format($cuenta->saldo_actual, 2) }}
            </p>
        </div>
    </div>

    <div class="flex justify-end mb-4">
        <a href="{{ route('empresas.caja.create-movimiento', [$empresa, $cuenta]) }}"
           class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo movimiento
        </a>
    </div>

    @if ($movimientos->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <p class="font-medium">Sin movimientos registrados</p>
            <p class="text-sm mt-1">Registra el primer ingreso o egreso</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Concepto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Referencia</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Ingreso</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Egreso</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Saldo</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($movimientos as $mov)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $mov->fecha->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                @if ($mov->tipo === 'ingreso')
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ingreso</span>
                                @else
                                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Egreso</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-800">{{ $mov->concepto }}</td>
                            <td class="px-4 py-3 text-sm text-slate-500 font-mono">{{ $mov->referencia }}</td>
                            <td class="px-4 py-3 text-sm text-right text-green-700 font-medium">
                                {{ $mov->tipo === 'ingreso' ? number_format($mov->monto, 2) : '' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-red-600 font-medium">
                                {{ $mov->tipo === 'egreso' ? number_format($mov->monto, 2) : '' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-semibold {{ $mov->saldo_resultante >= 0 ? 'text-slate-800' : 'text-red-600' }}">
                                {{ number_format($mov->saldo_resultante, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('empresas.caja.destroy-movimiento', [$empresa, $cuenta, $mov]) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar este movimiento?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-500 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
