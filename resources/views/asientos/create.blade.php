@extends('layouts.app')
@section('title', 'Nuevo asiento')
@section('content')

    <a href="{{ route('empresas.asientos.index', $empresa) }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Asientos
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Nuevo asiento</h1>
        <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
    </div>

    <form action="{{ route('empresas.asientos.store', $empresa) }}" method="POST"
          class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Número</label>
                <input type="text" name="numero" value="{{ old('numero') }}"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-slate-400"
                       placeholder="A-00001" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha</label>
                <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                <select name="tipo" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="apertura"   @selected(old('tipo')==='apertura')>Apertura</option>
                    <option value="compras"    @selected(old('tipo')==='compras')>Compras</option>
                    <option value="ventas"     @selected(old('tipo')==='ventas')>Ventas</option>
                    <option value="honorarios" @selected(old('tipo')==='honorarios')>Honorarios</option>
                    <option value="planillas"  @selected(old('tipo')==='planillas')>Planillas</option>
                    <option value="ajuste"     @selected(old('tipo','ajuste')==='ajuste')>Ajuste</option>
                    <option value="cierre"     @selected(old('tipo')==='cierre')>Cierre</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Glosa general</label>
            <input type="text" name="glosa" value="{{ old('glosa') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400"
                   placeholder="Descripción del asiento">
        </div>

        {{-- Tabla de líneas --}}
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Líneas del asiento</p>
            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table class="w-full" id="tabla-lineas">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-5/12">Cuenta</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-3/12">Glosa línea</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-1/12">Debe</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-1/12">Haber</th>
                            <th class="px-3 py-2 w-8"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @for ($i = 0; $i < 2; $i++)
                            <tr>
                                <td class="px-3 py-2">
                                    <select name="lineas[{{ $i }}][plan_cuenta_id]"
                                            class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-slate-400" required>
                                        <option value="">— Seleccionar —</option>
                                        @foreach ($cuentas as $cuenta)
                                            <option value="{{ $cuenta->id }}">{{ $cuenta->codigo }} — {{ $cuenta->denominacion }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" name="lineas[{{ $i }}][glosa]"
                                           class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-slate-400">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.01" name="lineas[{{ $i }}][debe]"
                                           class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm text-right campo-debe focus:outline-none focus:ring-1 focus:ring-slate-400"
                                           placeholder="0.00">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.01" name="lineas[{{ $i }}][haber]"
                                           class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm text-right campo-haber focus:outline-none focus:ring-1 focus:ring-slate-400"
                                           placeholder="0.00">
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <button type="button"
                                            class="quitar-fila text-slate-300 hover:text-red-500 transition-colors text-lg leading-none">×</button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <button type="button" id="agregar-fila"
                    class="mt-2 inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar línea
            </button>
        </div>

        {{-- Totales --}}
        <div class="flex items-center gap-6 bg-slate-50 rounded-lg px-4 py-3 border border-slate-200 text-sm">
            <span class="text-slate-600">Total Debe: <span id="total-debe" class="font-semibold text-slate-800">0.00</span></span>
            <span class="text-slate-600">Total Haber: <span id="total-haber" class="font-semibold text-slate-800">0.00</span></span>
            <span id="estado-cuadre" class="font-semibold"></span>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" id="btn-guardar"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Guardar asiento
            </button>
            <a href="{{ route('empresas.asientos.index', $empresa) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
        </div>
    </form>

    <script>
        let filaIndex = 2;
        const cuentasOptions = `{!! $cuentas->map(fn($c) => '<option value="'.$c->id.'">'.$c->codigo.' — '.$c->denominacion.'</option>')->implode('') !!}`;

        document.getElementById('agregar-fila').addEventListener('click', () => {
            const tbody = document.querySelector('#tabla-lineas tbody');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-3 py-2">
                    <select name="lineas[${filaIndex}][plan_cuenta_id]" class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-slate-400" required>
                        <option value="">— Seleccionar —</option>${cuentasOptions}
                    </select>
                </td>
                <td class="px-3 py-2">
                    <input type="text" name="lineas[${filaIndex}][glosa]" class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-slate-400">
                </td>
                <td class="px-3 py-2">
                    <input type="number" step="0.01" name="lineas[${filaIndex}][debe]" class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm text-right campo-debe focus:outline-none focus:ring-1 focus:ring-slate-400" placeholder="0.00">
                </td>
                <td class="px-3 py-2">
                    <input type="number" step="0.01" name="lineas[${filaIndex}][haber]" class="w-full border border-slate-300 rounded px-2 py-1.5 text-sm text-right campo-haber focus:outline-none focus:ring-1 focus:ring-slate-400" placeholder="0.00">
                </td>
                <td class="px-3 py-2 text-center">
                    <button type="button" class="quitar-fila text-slate-300 hover:text-red-500 transition-colors text-lg leading-none">×</button>
                </td>
            `;
            tbody.appendChild(tr);
            filaIndex++;
            recalcular();
        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('quitar-fila')) {
                const tbody = document.querySelector('#tabla-lineas tbody');
                if (tbody.rows.length > 1) {
                    e.target.closest('tr').remove();
                    recalcular();
                }
            }
        });

        document.addEventListener('input', (e) => {
            if (e.target.classList.contains('campo-debe') || e.target.classList.contains('campo-haber')) {
                recalcular();
            }
        });

        function recalcular() {
            let totalDebe = 0, totalHaber = 0;
            document.querySelectorAll('.campo-debe').forEach(el => totalDebe += parseFloat(el.value) || 0);
            document.querySelectorAll('.campo-haber').forEach(el => totalHaber += parseFloat(el.value) || 0);
            document.getElementById('total-debe').textContent  = totalDebe.toFixed(2);
            document.getElementById('total-haber').textContent = totalHaber.toFixed(2);
            const estado = document.getElementById('estado-cuadre');
            const cuadra = totalDebe > 0 && totalDebe.toFixed(2) === totalHaber.toFixed(2);
            estado.textContent  = cuadra ? '✓ Cuadrado' : '✗ No cuadra';
            estado.className    = cuadra ? 'font-semibold text-green-600' : 'font-semibold text-red-500';
            document.getElementById('btn-guardar').disabled = !cuadra;
            document.getElementById('btn-guardar').classList.toggle('opacity-50', !cuadra);
            document.getElementById('btn-guardar').classList.toggle('cursor-not-allowed', !cuadra);
        }
    </script>

@endsection