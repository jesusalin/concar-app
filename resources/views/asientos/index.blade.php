@extends('layouts.app')
@section('title', 'Asientos contables')
@section('content')

    {{-- Volver --}}
    <a href="{{ route('empresas.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Empresas
    </a>

    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Asientos Contables</h1>
            <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap justify-end">
            {{-- Reportes --}}
            <a href="{{ route('empresas.reportes.libro-diario', $empresa) }}"
               class="inline-flex items-center gap-1.5 text-sm border border-slate-300 hover:border-slate-400 text-slate-600 hover:text-slate-800 px-3 py-2 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Libro Diario
            </a>
            <a href="{{ route('empresas.reportes.libro-mayor', $empresa) }}"
               class="inline-flex items-center gap-1.5 text-sm border border-slate-300 hover:border-slate-400 text-slate-600 hover:text-slate-800 px-3 py-2 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 6h18M3 18h18"/>
                </svg>
                Libro Mayor
            </a>
            <a href="{{ route('empresas.reportes.balance', $empresa) }}"
               class="inline-flex items-center gap-1.5 text-sm border border-slate-300 hover:border-slate-400 text-slate-600 hover:text-slate-800 px-3 py-2 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Balance
            </a>
            @if (in_array($rolActual ?? null, ['administrador', 'contador']))
                <a href="{{ route('empresas.asientos.create', $empresa) }}"
                   class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo asiento
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">N°</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Glosa</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Debe</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Haber</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($asientos as $asiento)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3">
                            <a href="{{ route('empresas.asientos.show', [$empresa, $asiento]) }}"
                               class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-1 rounded font-mono font-semibold transition-colors">
                                {{ $asiento->numero }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $asiento->fecha }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $asiento->tipo }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-800">{{ $asiento->glosa }}</td>
                        <td class="px-4 py-3 text-sm text-right text-slate-700">{{ number_format($asiento->total_debe, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-right text-slate-700">{{ number_format($asiento->total_haber, 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            @if (in_array($rolActual ?? null, ['administrador', 'contador']))
                                <form action="{{ route('empresas.asientos.destroy', [$empresa, $asiento]) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar este asiento?')">
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