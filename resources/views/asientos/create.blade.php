@extends('layouts.app')
@section('title', 'Nuevo asiento')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Nuevo asiento — {{ $empresa->razon_social }}</h1>

    <form action="{{ route('empresas.asientos.store', $empresa) }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
        @csrf

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Número</label>
                <input type="text" name="numero" class="w-full border rounded p-2" placeholder="A-00001" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Fecha</label>
                <input type="date" name="fecha" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Tipo</label>
                <select name="tipo" class="w-full border rounded p-2">
                    <option value="apertura">Apertura</option>
                    <option value="compras">Compras</option>
                    <option value="ventas">Ventas</option>
                    <option value="honorarios">Honorarios</option>
                    <option value="planillas">Planillas</option>
                    <option value="ajuste" selected>Ajuste</option>
                    <option value="cierre">Cierre</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Glosa general</label>
            <input type="text" name="glosa" class="w-full border rounded p-2">
        </div>

        <table class="w-full border" id="tabla-lineas">
            <thead class="bg-slate-100 text-left text-sm uppercase">
                <tr>
                    <th class="p-2">Cuenta</th>
                    <th class="p-2">Glosa línea</th>
                    <th class="p-2">Debe</th>
                    <th class="p-2">Haber</th>
                    <th class="p-2"></th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 2; $i++)
                    <tr>
                        <td class="p-2">
                            <select name="lineas[{{ $i }}][plan_cuenta_id]" class="w-full border rounded p-1" required>
                                <option value="">— Seleccionar —</option>
                                @foreach ($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}">{{ $cuenta->codigo }} — {{ $cuenta->denominacion }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="p-2"><input type="text" name="lineas[{{ $i }}][glosa]" class="w-full border rounded p-1"></td>
                        <td class="p-2"><input type="number" step="0.01" name="lineas[{{ $i }}][debe]" class="w-24 border rounded p-1 campo-debe"></td>
                        <td class="p-2"><input type="number" step="0.01" name="lineas[{{ $i }}][haber]" class="w-24 border rounded p-1 campo-haber"></td>
                        <td class="p-2"><button type="button" class="text-red-600 quitar-fila">x</button></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <button type="button" id="agregar-fila" class="text-blue-600">+ Agregar línea</button>

        <div class="flex gap-6 text-sm font-semibold">
            <span>Total Debe: <span id="total-debe">0.00</span></span>
            <span>Total Haber: <span id="total-haber">0.00</span></span>
            <span id="estado-cuadre"></span>
        </div>

        <button class="bg-slate-800 text-white px-4 py-2 rounded">Guardar asiento</button>
    </form>

    <script>
        let filaIndex = {{ count($cuentas) ? 2 : 2 }};
        const cuentasOptions = `{!! $cuentas->map(fn($c) => '<option value="'.$c->id.'">'.$c->codigo.' — '.$c->denominacion.'</option>')->implode('') !!}`;

        document.getElementById('agregar-fila').addEventListener('click', () => {
            const tbody = document.querySelector('#tabla-lineas tbody');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="p-2"><select name="lineas[${filaIndex}][plan_cuenta_id]" class="w-full border rounded p-1" required><option value="">— Seleccionar —</option>${cuentasOptions}</select></td>
                <td class="p-2"><input type="text" name="lineas[${filaIndex}][glosa]" class="w-full border rounded p-1"></td>
                <td class="p-2"><input type="number" step="0.01" name="lineas[${filaIndex}][debe]" class="w-24 border rounded p-1 campo-debe"></td>
                <td class="p-2"><input type="number" step="0.01" name="lineas[${filaIndex}][haber]" class="w-24 border rounded p-1 campo-haber"></td>
                <td class="p-2"><button type="button" class="text-red-600 quitar-fila">x</button></td>
            `;
            tbody.appendChild(tr);
            filaIndex++;
            recalcular();
        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('quitar-fila')) {
                e.target.closest('tr').remove();
                recalcular();
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
            document.getElementById('total-debe').textContent = totalDebe.toFixed(2);
            document.getElementById('total-haber').textContent = totalHaber.toFixed(2);
            const estado = document.getElementById('estado-cuadre');
            if (totalDebe.toFixed(2) === totalHaber.toFixed(2) && totalDebe > 0) {
                estado.textContent = '✓ Cuadrado';
                estado.className = 'text-green-600';
            } else {
                estado.textContent = '✗ No cuadra';
                estado.className = 'text-red-600';
            }
        }
    </script>
@endsection
