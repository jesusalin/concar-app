@extends('layouts.app')
@section('title', 'Nueva empresa')
@section('content')

    <a href="{{ route('empresas.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Empresas
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Nueva empresa</h1>
        <p class="text-slate-500 text-sm mt-0.5">Completa los datos para registrar la empresa</p>
    </div>

    <form action="{{ route('empresas.store') }}" method="POST"
          class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5 max-w-lg">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">RUC</label>
            <input type="text" name="ruc" maxlength="11" value="{{ old('ruc') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400"
                   placeholder="Ej: 20123456789" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Razón Social</label>
            <input type="text" name="razon_social" value="{{ old('razon_social') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400"
                   placeholder="Ej: Mi Empresa S.A.C." required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Moneda</label>
            <select name="moneda" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                <option value="PEN" @selected(old('moneda','PEN')==='PEN')>Soles (PEN)</option>
                <option value="USD" @selected(old('moneda')==='USD')>Dólares (USD)</option>
                <option value="EUR" @selected(old('moneda')==='EUR')>Euros (EUR)</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de inicio de actividades</label>
            <input type="date" name="fecha_inicio_actividades" value="{{ old('fecha_inicio_actividades') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Guardar empresa
            </button>
            <a href="{{ route('empresas.index') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
        </div>
    </form>

@endsection