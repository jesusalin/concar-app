@extends('layouts.app')
@section('title', 'Historial CxC / CxP')
@section('content')
    <a href="{{ route('empresas.cxcp.index', $empresa) }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver
    </a>

    <h1 class="text-2xl font-bold text-slate-900 mb-6">Historial — {{ $empresa->razon_social }}</h1>

    @if ($canceladas->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <p class="font-medium">No hay cuentas canceladas aún.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Tercero</th>
                        <th class="px-4 py-3">Origen</th>
                        <th class="px-4 py-3 text-right">Monto</th>
                        <th class="px-4 py-3">Cancelado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($canceladas as $item)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <span @class([
                                    'text-xs px-2 py-0.5 rounded-full font-medium',
                                    'bg-green-100 text-green-700' => $item->tipo === 'cobrar',
                                    'bg-red-100 text-red-700' => $item->tipo === 'pagar',
                                ])>{{ $item->tipo === 'cobrar' ? 'Por cobrar' : 'Por pagar' }}</span>
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $item->tercero->razon_social }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $item->origen ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-semibold">{{ number_format($item->monto_original, 2) }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $item->updated_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
