@extends('layouts.app')
@section('title', 'Libro Diario')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Libro Diario — {{ $empresa->razon_social }}</h1>

    @foreach ($asientos as $asiento)
        <div class="bg-white rounded shadow mb-4">
            <div class="bg-slate-100 p-3 flex justify-between text-sm font-semibold">
                <span>{{ $asiento->numero }} · {{ $asiento->fecha }} · {{ $asiento->tipo }}</span>
                <span>{{ $asiento->glosa }}</span>
            </div>
            <table class="w-full">
                <tbody>
                    @foreach ($asiento->detalles as $d)
                        <tr class="border-t">
                            <td class="p-2">{{ $d->planCuenta->codigo }} — {{ $d->planCuenta->denominacion }}</td>
                            <td class="p-2">{{ $d->glosa }}</td>
                            <td class="p-2 text-right w-32">{{ number_format($d->debe, 2) }}</td>
                            <td class="p-2 text-right w-32">{{ number_format($d->haber, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <a href="{{ route('empresas.asientos.index', $empresa) }}" class="text-blue-600">← Volver</a>
@endsection
