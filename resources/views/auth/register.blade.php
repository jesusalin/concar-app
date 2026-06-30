<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear cuenta — ConCar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-900">

    <!-- Fondo decorativo -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl"></div>

    <div class="relative w-full max-w-sm px-4 py-8">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-slate-800 mb-3 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                    <path d="M3 7l9 6 9-6"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">ConCar Laravel</h1>
            <p class="text-slate-400 text-sm mt-1">Crea tu cuenta</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-2xl">
            @if ($errors->any())
                <div class="bg-red-100 text-red-800 text-sm px-3 py-2 rounded mb-4">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre completo</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-slate-800 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-slate-800 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full border rounded-lg p-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-slate-800 transition">
                        <button type="button" data-toggle="password" tabindex="-1"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-700">
                            <svg class="icon-eye-open w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="icon-eye-closed w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 19c-7 0-11-7-11-7a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 7 11 7a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><path d="M1 1l22 22"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Medidor de seguridad -->
                    <div class="mt-2">
                        <div class="flex gap-1 h-1.5">
                            <div class="strength-bar flex-1 rounded bg-slate-200 transition-colors"></div>
                            <div class="strength-bar flex-1 rounded bg-slate-200 transition-colors"></div>
                            <div class="strength-bar flex-1 rounded bg-slate-200 transition-colors"></div>
                            <div class="strength-bar flex-1 rounded bg-slate-200 transition-colors"></div>
                        </div>
                        <p id="strength-label" class="text-xs text-slate-400 mt-1">Mínimo 8 caracteres</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar contraseña</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full border rounded-lg p-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-slate-800 transition">
                        <button type="button" data-toggle="password_confirmation" tabindex="-1"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-700">
                            <svg class="icon-eye-open w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="icon-eye-closed w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 19c-7 0-11-7-11-7a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 7 11 7a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><path d="M1 1l22 22"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-slate-800 text-white rounded-lg py-2.5 font-medium hover:bg-slate-700 transition shadow-lg shadow-slate-900/20">
                    Crear cuenta
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-slate-300 mt-5">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="font-semibold text-white hover:underline">Inicia sesión</a>
        </p>
    </div>

    <script>
        // Toggle de mostrar/ocultar para ambos campos de contraseña
        document.querySelectorAll('[data-toggle]').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.toggle);
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                btn.querySelector('.icon-eye-open').classList.toggle('hidden', isHidden);
                btn.querySelector('.icon-eye-closed').classList.toggle('hidden', !isHidden);
            });
        });

        // Medidor de seguridad de contraseña
        const passwordField = document.getElementById('password');
        const bars = document.querySelectorAll('.strength-bar');
        const label = document.getElementById('strength-label');

        const colores = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
        const textos = ['Muy débil', 'Débil', 'Aceptable', 'Fuerte'];

        passwordField.addEventListener('input', () => {
            const val = passwordField.value;
            let puntos = 0;
            if (val.length >= 8) puntos++;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) puntos++;
            if (/[0-9]/.test(val)) puntos++;
            if (/[^A-Za-z0-9]/.test(val) && val.length >= 10) puntos++;

            bars.forEach((bar, i) => {
                bar.className = 'strength-bar flex-1 rounded transition-colors ' +
                    (val.length === 0 ? 'bg-slate-200' : (i < puntos ? colores[Math.max(puntos - 1, 0)] : 'bg-slate-200'));
            });

            label.textContent = val.length === 0
                ? 'Mínimo 8 caracteres'
                : textos[Math.max(puntos - 1, 0)];
        });
    </script>
</body>
</html>
