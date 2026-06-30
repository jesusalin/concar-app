<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión — ConCar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-900">

    <!-- Fondo decorativo -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl"></div>

    <div class="relative w-full max-w-sm px-4">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-slate-800 mb-3 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                    <path d="M3 7l9 6 9-6"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">ConCar Laravel</h1>
            <p class="text-slate-400 text-sm mt-1">Sistema contable</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-2xl">
            @if (session('status'))
                <div class="bg-green-100 text-green-800 text-sm px-3 py-2 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 text-sm px-3 py-2 rounded mb-4">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-slate-800 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full border rounded-lg p-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-slate-800 transition">
                        <button type="button" id="toggle-password" tabindex="-1"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-700">
                            <svg id="icon-eye-open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="icon-eye-closed" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 19c-7 0-11-7-11-7a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 7 11 7a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                <path d="M1 1l22 22"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600">
                        <input type="checkbox" name="remember">
                        Recordarme
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-slate-600 hover:underline">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-slate-800 text-white rounded-lg py-2.5 font-medium hover:bg-slate-700 transition shadow-lg shadow-slate-900/20">
                    Iniciar sesión
                </button>
            </form>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-sm text-slate-300 mt-5">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="font-semibold text-white hover:underline">Regístrate aquí</a>
            </p>
        @endif
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('toggle-password');
        const eyeOpen = document.getElementById('icon-eye-open');
        const eyeClosed = document.getElementById('icon-eye-closed');

        toggleBtn.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
        });
    </script>
</body>
</html>
