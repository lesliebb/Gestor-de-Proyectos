<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GesPro') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Script Anti-Flicker para Dark Mode --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="antialiased text-gray-900 dark:text-gray-100 font-sans selection:bg-indigo-500 selection:text-white overflow-x-hidden">

    <div class="fixed inset-0 -z-10 bg-gray-50 dark:bg-gray-950 transition-colors duration-300">
        <div class="absolute top-0 -left-4 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-10 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-10 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-20 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-10 animate-blob animation-delay-4000"></div>

        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
    </div>

    <div class="relative min-h-screen flex flex-col justify-center items-center">

        {{-- NAVBAR --}}
        <div class="absolute top-0 w-full p-6 flex justify-between items-center max-w-7xl mx-auto z-20">
            <div class="flex items-center gap-2">
                {{-- SECCIÓN DEL LOGO MODIFICADA (Tamaño aumentado a w-12 h-12) --}}
                <img src="{{ asset('images/LogoClaro.ico') }}"
                     alt="GesPro Logo"
                     class="w-12 h-12 object-contain block dark:hidden">

                <img src="{{ asset('images/LogoOscuro.ico') }}"
                     alt="GesPro Logo Dark"
                     class="w-12 h-12 object-contain hidden dark:block">
                {{-- FIN SECCIÓN DEL LOGO MODIFICADA --}}

                <span class="font-bold text-xl tracking-tight dark:text-white">GesPro</span>
            </div>

            <div class="flex items-center gap-4">
                <button id="theme-toggle-welcome" type="button" class="p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg id="theme-toggle-dark-icon-welcome" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon-welcome" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>

                @if (Route::has('login'))
                    <div class="hidden sm:flex gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="font-semibold text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white transition sm: mt-1.5">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg text-sm font-bold hover:opacity-90 transition shadow-lg">Registrarse</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-6 pt-20 pb-16 lg:pt-32 text-center relative z-10">

            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 text-gray-900 dark:text-white">
                Gestión de <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">Proyectos</span>
            </h1>

            <p class="max-w-2xl mx-auto text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-10 leading-relaxed">
                Centraliza eventos, forma equipos multidisciplinarios y evalúa resultados en tiempo real. La herramienta definitiva para docentes y estudiantes.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-lg shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all duration-300">
                        Ir al Panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-lg shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all duration-300">
                        Comenzar Ahora
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl font-bold text-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:-translate-y-1 transition-all duration-300">
                        Crear Cuenta
                    </a>
                @endauth
            </div>
        </div>

        {{-- FEATURES GRID --}}
        <div class="max-w-7xl mx-auto px-6 pb-20 w-full">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:border-indigo-500/30 dark:hover:border-indigo-500/30 hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Equipos Dinámicos</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Fomenta la colaboración. Crea equipos multidisciplinarios combinando talento de diversas carreras.
                    </p>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:border-purple-500/30 dark:hover:border-purple-500/30 hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center text-purple-600 dark:text-purple-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Evaluación en Tiempo Real</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Sistema de rúbricas digital. Los jueces evalúan y los resultados se calculan instantáneamente.
                    </p>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:border-pink-500/30 dark:hover:border-pink-500/30 hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-pink-100 dark:bg-pink-900/30 rounded-2xl flex items-center justify-center text-pink-600 dark:text-pink-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Resultados Automáticos</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                        Generación automática de rankings, podios y constancias de participación listas para descargar.
                    </p>
                </div>

            </div>
        </div>

        <footer class="absolute bottom-4 text-center w-full text-xs text-gray-400 dark:text-gray-600">
            &copy; {{ date('Y') }} GesPro System. Todos los derechos reservados.
        </footer>
    </div>

    {{-- Script para Toggle Tema --}}
    <script>
        const welcomeToggleBtn = document.getElementById('theme-toggle-welcome');
        const welcomeDarkIcon = document.getElementById('theme-toggle-dark-icon-welcome');
        const welcomeLightIcon = document.getElementById('theme-toggle-light-icon-welcome');

        function updateIcons() {
            if (document.documentElement.classList.contains('dark')) {
                welcomeLightIcon.classList.remove('hidden');
                welcomeDarkIcon.classList.add('hidden');
            } else {
                welcomeLightIcon.classList.add('hidden');
                welcomeDarkIcon.classList.remove('hidden');
            }
        }

        updateIcons();

        welcomeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            updateIcons();
        });
    </script>

    {{-- Animación CSS para los Blobs --}}
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>