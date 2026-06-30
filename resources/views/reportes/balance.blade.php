@extends('layouts.app')
@section('title', 'Balance de Comprobación')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Balance de Comprobación — {{ $empresa->razon_social }}</h1>

    <table class="w-full bg-white rounded shadow">
        <thead class="bg-slate-100 text-left text-sm uppercase">
            <tr>
                <th class="p-3">Cuenta</th>
                <th class="p-3 text-right">Total Debe</th>
                <th class="p-3 text-right">Total Haber</th>
                <th class="p-3 text-right">Saldo Deudor</th>
                <th class="p-3 text-right">Saldo Acreedor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cuentas as $cuenta)
                <tr class="border-t">
                    <td class="p-3">{{ $cuenta->codigo }} — {{ $cuenta->denominacion }}</td>
                    <td class="p-3 text-right">{{ number_format($cuenta->total_debe, 2) }}</td>
                    <td class="p-3 text-right">{{ number_format($cuenta->total_haber, 2) }}</td>
                    <td class="p-3 text-right">{{ $cuenta->saldo > 0 ? number_format($cuenta->saldo, 2) : '' }}</td>
                    <td class="p-3 text-right">{{ $cuenta->saldo < 0 ? number_format(abs($cuenta->saldo), 2) : '' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="font-semibold border-t bg-slate-50">
            <tr>
                <td class="p-3">Totales</td>
                <td class="p-3 text-right">{{ number_format($totalGeneralDebe, 2) }}</td>
                <td class="p-3 text-right">{{ number_format($totalGeneralHaber, 2) }}</td>
                <td class="p-3" colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <a href="{{ route('empresas.asientos.index', $empresa) }}" class="text-blue-600 mt-4 inline-block">← Volver</a>
@endsection
