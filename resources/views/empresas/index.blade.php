@extends('layouts.app')
@section('title', 'Empresas')
@section('content')
 
    {{-- Encabezado --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Mis Empresas</h1>
            <p class="text-slate-500 text-sm mt-1">Selecciona una empresa para gestionarla</p>
        </div>
        <a href="{{ route('empresas.create') }}"
           class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva empresa
        </a>
    </div>
 
    @if ($empresas->isEmpty())
        <div class="text-center py-16 text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16M3 21h18M9 7h1m-1 4h1m4-4h1m-1 4h1M9 21v-4a2 2 0 012-2h2a2 2 0 012 2v4"/>
            </svg>
            <p class="font-medium">No tienes empresas registradas</p>
            <p class="text-sm mt-1">Crea tu primera empresa para comenzar</p>
        </div>
    @else
        <div class="grid gap-5 sm:grid-cols-1 lg:grid-cols-2">
            @foreach ($empresas as $empresa)
                @php $rol = $empresa->rolDe(auth()->id()); @endphp
 
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
 
                    {{-- Cabecera de la tarjeta --}}
                    <div class="bg-slate-800 px-5 py-4 flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-xs font-mono tracking-wider">RUC {{ $empresa->ruc }}</p>
                            <h2 class="text-white font-semibold text-lg leading-tight mt-0.5">{{ $empresa->razon_social }}</h2>
                        </div>
                        <span class="bg-slate-700 text-slate-300 text-xs px-2 py-1 rounded-full whitespace-nowrap">
                            {{ $empresa->moneda }}
                        </span>
                    </div>
 
                    {{-- Cuerpo de la tarjeta --}}
                    <div class="px-5 py-4">
 
                        {{-- Badge de rol --}}
                        <div class="flex items-center gap-2 mb-4">
                            @if ($rol === 'administrador')
                                <span class="inline-flex items-center gap-1 text-xs font-medium bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Administrador
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ ucfirst($rol) }}
                                </span>
                            @endif
                        </div>
 
                        {{-- Módulos principales --}}
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Módulos</p>
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <a href="{{ route('empresas.plancuentas.index', $empresa) }}"
                               class="flex flex-col items-center gap-1 p-3 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-slate-600 text-xs font-medium text-center transition-colors border border-slate-100 hover:border-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Plan de cuentas
                            </a>
                            <a href="{{ route('empresas.terceros.index', $empresa) }}"
                               class="flex flex-col items-center gap-1 p-3 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-slate-600 text-xs font-medium text-center transition-colors border border-slate-100 hover:border-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                                </svg>
                                Terceros
                            </a>
                            <a href="{{ route('empresas.compras.index', $empresa) }}"
                               class="flex flex-col items-center gap-1 p-3 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-slate-600 text-xs font-medium text-center transition-colors border border-slate-100 hover:border-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3"/>
                                </svg>
                                Compras
                            </a>
                            <a href="{{ route('empresas.ventas.index', $empresa) }}"
                               class="flex flex-col items-center gap-1 p-3 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-slate-600 text-xs font-medium text-center transition-colors border border-slate-100 hover:border-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Ventas
                            </a>
                            <a href="{{ route('empresas.asientos.index', $empresa) }}"
                               class="flex flex-col items-center gap-1 p-3 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-slate-600 text-xs font-medium text-center transition-colors border border-slate-100 hover:border-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Asientos
                            </a>
                            <a href="{{ route('empresas.reportes.balance', $empresa) }}"
                               class="flex flex-col items-center gap-1 p-3 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-blue-700 text-slate-600 text-xs font-medium text-center transition-colors border border-slate-100 hover:border-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Balance
                            </a>
                        </div>
 
                        {{-- Acciones de administrador --}}
                        @if ($rol === 'administrador')
                            <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('empresas.configuracion.edit', $empresa) }}"
                                       class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-slate-800 transition-colors px-2 py-1 rounded hover:bg-slate-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Configuración
                                    </a>
                                    <a href="{{ route('empresas.usuarios.index', $empresa) }}"
                                       class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-slate-800 transition-colors px-2 py-1 rounded hover:bg-slate-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        Usuarios
                                    </a>
                                </div>
                                <form action="{{ route('empresas.destroy', $empresa) }}" method="POST"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar {{ $empresa->razon_social }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs text-red-500 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
 
@endsection