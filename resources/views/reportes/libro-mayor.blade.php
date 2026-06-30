@extends('layouts.app')
@section('title', 'Libro Mayor')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Libro Mayor — {{ $empresa->razon_social }}</h1>

    @foreach ($cuentas as $cuenta)
        @php $saldo = 0; @endphp
        <div class="bg-white rounded shadow mb-4">
            <div class="bg-slate-100 p-3 font-semibold">{{ $cuenta->codigo }} — {{ $cuenta->denominacion }}</div>
            <table class="w-full">
                <thead class="text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="p-2">Fecha</th>
                        <th class="p-2">Asiento</th>
                        <th class="p-2">Glosa</th>
                        <th class="p-2 text-right">Debe</th>
                        <th class="p-2 text-right">Haber</th>
                        <th class="p-2 text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cuenta->detalles as $d)
                        @php $saldo += $d->debe - $d->haber; @endphp
                        <tr class="border-t">
                            <td class="p-2">{{ $d->asiento->fecha }}</td>
                            <td class="p-2">{{ $d->asiento->numero }}</td>
                            <td class="p-2">{{ $d->glosa }}</td>
                            <td class="p-2 text-right">{{ number_format($d->debe, 2) }}</td>
                            <td class="p-2 text-right">{{ number_format($d->haber, 2) }}</td>
                            <td class="p-2 text-right font-semibold">{{ number_format($saldo, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <a href="{{ route('empresas.asientos.index', $empresa) }}" class="text-blue-600">← Volver</a>
@endsection
