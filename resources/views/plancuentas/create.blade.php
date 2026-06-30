@extends('layouts.app')
@section('title', 'Nueva cuenta')
@section('content')

    <a href="{{ route('empresas.plancuentas.index', $empresa) }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Plan de Cuentas
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Nueva cuenta</h1>
        <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
    </div>

    <form action="{{ route('empresas.plancuentas.store', $empresa) }}" method="POST"
          class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5 max-w-lg">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Código (PCGE)</label>
            <input type="text" name="codigo" value="{{ old('codigo') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-slate-400"
                   placeholder="Ej: 1011" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Denominación</label>
            <input type="text" name="denominacion" value="{{ old('denominacion') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400"
                   placeholder="Ej: Caja" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nivel</label>
            <select name="nivel" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                <option value="elemento"       @selected(old('nivel')==='elemento')>Elemento</option>
                <option value="cuenta"         @selected(old('nivel')==='cuenta')>Cuenta</option>
                <option value="subcuenta"      @selected(old('nivel')==='subcuenta')>Subcuenta</option>
                <option value="divisionaria"   @selected(old('nivel','divisionaria')==='divisionaria')>Divisionaria</option>
                <option value="subdivisionaria"@selected(old('nivel')==='subdivisionaria')>Subdivisionaria</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Cuenta padre <span class="text-slate-400 font-normal">(opcional)</span></label>
            <select name="cuenta_padre_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                <option value="">— Ninguna —</option>
                @foreach ($padres as $padre)
                    <option value="{{ $padre->id }}" @selected(old('cuenta_padre_id')==$padre->id)>
                        {{ $padre->codigo }} — {{ $padre->denominacion }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="acepta_movimiento" value="1" id="am"
                   @checked(old('acepta_movimiento', true))
                   class="w-4 h-4 rounded border-slate-300">
            <label for="am" class="text-sm text-slate-700">Acepta movimientos <span class="text-slate-400">(cuenta hoja, usable en asientos)</span></label>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Guardar cuenta
            </button>
            <a href="{{ route('empresas.plancuentas.index', $empresa) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
        </div>
    </form>

@endsection