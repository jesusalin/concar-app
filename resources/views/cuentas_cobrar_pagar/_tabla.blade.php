<table class="w-full text-sm">
    <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500 border-b border-slate-200">
        <tr>
            <th class="px-4 py-3">Tercero</th>
            <th class="px-4 py-3">Origen</th>
            <th class="px-4 py-3">Emisión</th>
            <th class="px-4 py-3">Vencimiento</th>
            <th class="px-4 py-3 text-right">Original</th>
            <th class="px-4 py-3 text-right">Pagado</th>
            <th class="px-4 py-3 text-right">Pendiente</th>
            <th class="px-4 py-3">Estado</th>
            <th class="px-4 py-3"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                <td class="px-4 py-3 font-medium text-slate-800">{{ $item->tercero->razon_social }}</td>
                <td class="px-4 py-3 text-slate-500">{{ $item->origen ?? '—' }}</td>
                <td class="px-4 py-3 text-slate-500">{{ $item->fecha_emision }}</td>
                <td class="px-4 py-3 text-slate-500">
                    @if ($item->fecha_vencimiento)
                        <span @class(['text-red-600 font-medium' => $item->fecha_vencimiento < now()->toDateString()])>
                            {{ $item->fecha_vencimiento }}
                        </span>
                    @else
                        —
                    @endif
                </td>
                <td class="px-4 py-3 text-right">{{ number_format($item->monto_original, 2) }}</td>
                <td class="px-4 py-3 text-right text-slate-500">{{ number_format($item->monto_pagado, 2) }}</td>
                <td class="px-4 py-3 text-right font-semibold {{ $tipo === 'cobrar' ? 'text-green-700' : 'text-red-700' }}">
                    {{ number_format($item->saldo_pendiente, 2) }}
                </td>
                <td class="px-4 py-3">
                    <span @class([
                        'text-xs px-2 py-0.5 rounded-full font-medium',
                        'bg-yellow-100 text-yellow-700' => $item->estado === 'pendiente',
                        'bg-blue-100 text-blue-700' => $item->estado === 'parcial',
                        'bg-green-100 text-green-700' => $item->estado === 'cancelado',
                    ])>{{ ucfirst($item->estado) }}</span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <form action="{{ route('empresas.cxcp.pago', [$empresa, $item]) }}" method="POST"
                              onsubmit="return confirm('¿Registrar pago total del saldo pendiente?')">
                            @csrf
                            <input type="hidden" name="monto" value="{{ $item->saldo_pendiente }}">
                            <button class="text-xs text-emerald-600 hover:underline">Pagar</button>
                        </form>
                        <form action="{{ route('empresas.cxcp.destroy', [$empresa, $item]) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta cuenta?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-500 hover:underline">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
