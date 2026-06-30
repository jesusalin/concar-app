@extends('layouts.app')
@section('title', 'Usuarios de la empresa')
@section('content')

    {{-- Volver --}}
    <a href="{{ route('empresas.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Empresas
    </a>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Usuarios con acceso</h1>
        <p class="text-slate-500 text-sm mt-0.5">{{ $empresa->razon_social }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Correo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Rol</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($asignaciones as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-slate-800">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            @if ($user->pivot->rol === 'administrador')
                                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Administrador</span>
                            @elseif ($user->pivot->rol === 'contador')
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Contador</span>
                            @else
                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ ucfirst($user->pivot->rol) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form action="{{ route('empresas.usuarios.destroy', [$empresa, $user->pivot->id]) }}" method="POST"
                                  onsubmit="return confirm('¿Quitar el acceso a {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-500 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                    Quitar acceso
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Formulario dar acceso --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-lg">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Dar acceso a un usuario</h2>
        <form action="{{ route('empresas.usuarios.store', $empresa) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Correo del usuario</label>
                <p class="text-xs text-slate-400 mb-1">El usuario ya debe estar registrado en el sistema</p>
                <input type="email" name="email" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Rol</label>
                <select name="rol" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="administrador">Administrador — acceso total</option>
                    <option value="contador" selected>Contador — puede registrar y editar</option>
                    <option value="asistente">Asistente — solo lectura</option>
                </select>
            </div>
            <button class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Dar acceso
            </button>
        </form>
    </div>

@endsection