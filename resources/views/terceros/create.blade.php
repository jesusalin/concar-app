@extends('layouts.app')
@section('title', 'Nuevo tercero')
@section('content')

    <a href="{{ route('empresas.terceros.index', $empresa) }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Terceros
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Nuevo cliente / proveedor</h1>
        <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
    </div>

    <form action="{{ route('empresas.terceros.store', $empresa) }}" method="POST"
          class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5 max-w-lg">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de documento</label>
                <select name="tipo_documento" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="RUC"       @selected(old('tipo_documento','RUC')==='RUC')>RUC</option>
                    <option value="DNI"       @selected(old('tipo_documento')==='DNI')>DNI</option>
                    <option value="CE"        @selected(old('tipo_documento')==='CE')>Carnet de Extranjería</option>
                    <option value="PASAPORTE" @selected(old('tipo_documento')==='PASAPORTE')>Pasaporte</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Número</label>
                <input type="text" name="numero_documento" value="{{ old('numero_documento') }}"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-slate-400"
                       required>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Razón Social / Nombre</label>
            <input type="text" name="razon_social" value="{{ old('razon_social') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400"
                   required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
            <select name="tipo" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                <option value="cliente"   @selected(old('tipo')==='cliente')>Cliente</option>
                <option value="proveedor" @selected(old('tipo')==='proveedor')>Proveedor</option>
                <option value="ambos"     @selected(old('tipo','ambos')==='ambos')>Ambos</option>
            </select>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Guardar
            </button>
            <a href="{{ route('empresas.terceros.index', $empresa) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
        </div>
    </form>

@endsection