@extends('layouts.app')
@section('title', 'Mis Empresas')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Mis Empresas</h1>
            <p class="text-slate-500 text-sm mt-0.5">Selecciona una empresa para gestionar su contabilidad</p>
        </div>
        <a href="{{ route('empresas.create') }}" class="bg-slate-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-700 transition shadow-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Nueva empresa
        </a>
    </div>

    @if ($empresas->isEmpty())
        <div class="bg-white border border-dashed border-slate-300 rounded-xl p-12 text-center">
            <p class="text-slate-500">Aún no tienes empresas registradas.</p>
            <a href="{{ route('empresas.create') }}" class="text-slate-800 font-semibold hover:underline mt-2 inline-block">Crea tu primera empresa →</a>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($empresas as $empresa)
            @php $rol = $empresa->rolDe(auth()->id()); @endphp
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition">

                <div class="p-5 border-b border-slate-100 flex justify-between items-start">
                    <div>
                        <h2 class="font-semibold text-lg text-slate-900">{{ $empresa->razon_social }}</h2>
                        <p class="text-slate-500 text-sm">RUC {{ $empresa->ruc }} · {{ $empresa->moneda }}</p>
                    </div>
                    <span @class([
                        'text-xs font-medium px-2.5 py-1 rounded-full whitespace-nowrap',
                        'bg-emerald-50 text-emerald-700' => $rol === 'administrador',
                        'bg-blue-50 text-blue-700' => $rol === 'contador',
                        'bg-slate-100 text-slate-600' => $rol === 'asistente',
                    ])>
                        {{ ucfirst($rol) }}
                    </span>
                </div>

                <div class="p-5 grid grid-cols-3 gap-2 text-sm">
                    <a href="{{ route('empresas.plancuentas.index', $empresa) }}" class="flex flex-col items-center gap-1.5 p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                        Plan de cuentas
                    </a>
                    <a href="{{ route('empresas.terceros.index', $empresa) }}" class="flex flex-col items-center gap-1.5 p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        Terceros
                    </a>
                    <a href="{{ route('empresas.compras.index', $empresa) }}" class="flex flex-col items-center gap-1.5 p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293A1 1 0 005 17h12"/><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/></svg>
                        Compras
                    </a>
                    <a href="{{ route('empresas.ventas.index', $empresa) }}" class="flex flex-col items-center gap-1.5 p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        Ventas
                    </a>
                    <a href="{{ route('empresas.caja.index', $empresa) }}" class="flex flex-col items-center gap-1.5 p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Caja/Bancos
                    </a>
                    <a href="{{ route('empresas.cxcp.index', $empresa) }}" class="flex flex-col items-center gap-1.5 p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 14l2 2 4-4"/><path d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg>
                        CxC / CxP
                    </a>
                    <a href="{{ route('empresas.asientos.index', $empresa) }}" class="flex flex-col items-center gap-1.5 p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                        Asientos
                    </a>
                    <a href="{{ route('empresas.reportes.balance', $empresa) }}" class="flex flex-col items-center gap-1.5 p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
                        Balance
                    </a>
                </div>

                @if ($rol === 'administrador')
                    <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex justify-between items-center text-sm">
                        <div class="flex gap-4">
                            <a href="{{ route('empresas.configuracion.edit', $empresa) }}" class="text-slate-500 hover:text-slate-800 transition">Configuración</a>
                            <a href="{{ route('empresas.usuarios.index', $empresa) }}" class="text-slate-500 hover:text-slate-800 transition">Usuarios</a>
                        </div>
                        <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" onsubmit="return confirm('¿Eliminar empresa?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 transition">Eliminar</button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endsection
