@extends('layouts.app')
@section('title', 'Detalle de asiento')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Asiento {{ $asiento->numero }} — {{ $empresa->razon_social }}</h1>
    <p class="mb-4 text-slate-600">{{ $asiento->fecha }} · {{ $asiento->tipo }} · {{ $asiento->glosa }}</p>

    <table class="w-full bg-white rounded shadow">
        <thead class="bg-slate-100 text-left text-sm uppercase">
            <tr>
                <th class="p-3">Cuenta</th>
                <th class="p-3">Glosa</th>
                <th class="p-3">Debe</th>
                <th class="p-3">Haber</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asiento->detalles as $d)
                <tr class="border-t">
                    <td class="p-3">{{ $d->planCuenta->codigo }} — {{ $d->planCuenta->denominacion }}</td>
                    <td class="p-3">{{ $d->glosa }}</td>
                    <td class="p-3">{{ number_format($d->debe, 2) }}</td>
                    <td class="p-3">{{ number_format($d->haber, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="font-semibold border-t">
            <tr>
                <td class="p-3" colspan="2">Totales</td>
                <td class="p-3">{{ number_format($asiento->total_debe, 2) }}</td>
                <td class="p-3">{{ number_format($asiento->total_haber, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <a href="{{ route('empresas.asientos.index', $empresa) }}" class="text-blue-600 mt-4 inline-block">← Volver</a>
@endsection
