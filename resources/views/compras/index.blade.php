@extends('layouts.app')
@section('title', 'Registro de Compras')
@section('content')

    {{-- Volver --}}
    <a href="{{ route('empresas.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Empresas
    </a>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Registro de Compras</h1>
            <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
        </div>
        @if (in_array($rolActual ?? null, ['administrador', 'contador']))
            <a href="{{ route('empresas.compras.create', $empresa) }}"
               class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva compra
            </a>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Comprobante</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Proveedor</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Base</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">IGV</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Asiento</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($compras as $c)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $c->fecha_emision }}</td>
                        <td class="px-4 py-3 text-sm font-mono text-slate-700">{{ $c->tipo_comprobante }} {{ $c->serie }}-{{ $c->numero }}</td>
                        <td class="px-4 py-3 text-sm text-slate-800">{{ $c->tercero->razon_social }}</td>
                        <td class="px-4 py-3 text-sm text-right text-slate-700">{{ number_format($c->base_imponible, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-right text-slate-500">{{ number_format($c->igv, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-right font-semibold text-slate-800">{{ number_format($c->total, 2) }}</td>
                        <td class="px-4 py-3">
                            @if ($c->asiento)
                                <a href="{{ route('empresas.asientos.show', [$empresa, $c->asiento]) }}"
                                   class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-1 rounded font-mono transition-colors">
                                    {{ $c->asiento->numero }}
                                </a>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if (in_array($rolActual ?? null, ['administrador', 'contador']))
                                <form action="{{ route('empresas.compras.destroy', [$empresa, $c]) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta compra y su asiento?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-500 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection