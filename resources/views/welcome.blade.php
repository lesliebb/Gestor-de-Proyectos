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
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        /* Animación para los blobs de fondo */
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
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

        /* Estilos para el libro 3D */
        .perspective-1000 {
            perspective: 1000px;
        }

        .preserve-3d {
            transform-style: preserve-3d;
        }

        .page-content {
            transform: translateZ(30px);
        }

        /* Efecto de páginas con separación */
        .page-shadow {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Animación del trofeo flotante */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes entrance {
            0% {
                opacity: 0;
                transform: scale(0.5) rotate(-10deg);
            }

            100% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        .animate-entrance {
            animation: entrance 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Textura de papel */
        .paper-texture {
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.05'/%3E%3C/svg%3E");
        }

        /* Efecto de grosor de páginas */
        .book-thickness {
            position: relative;
        }

        .book-thickness::after {
            content: '';
            position: absolute;
            top: 2px;
            bottom: 2px;
            right: -12px;
            width: 12px;
            background: linear-gradient(to right, #e5e7eb, #f3f4f6 20%, #d1d5db 40%, #f3f4f6 60%, #d1d5db 80%, #f9fafb);
            transform: rotateY(90deg);
            transform-origin: left;
            border-radius: 0 4px 4px 0;
        }

        /* Ocultar scrollbar pero permitir scroll */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body
    class="antialiased text-gray-900 dark:text-gray-100 font-sans selection:bg-indigo-500 selection:text-white overflow-x-hidden">

    <div class="fixed inset-0 -z-10 bg-gray-50 dark:bg-gray-950 transition-colors duration-300">
        <div
            class="absolute top-0 -left-4 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-10 animate-blob">
        </div>
        <div
            class="absolute top-0 -right-4 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-10 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-32 left-20 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-10 animate-blob animation-delay-4000">
        </div>

        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
    </div>

    {{-- Canvas para Partículas de Fondo --}}
    <canvas id="particles-canvas" class="fixed inset-0 z-0 pointer-events-none"></canvas>

    {{-- Trofeo animado que sigue el scroll (Imagen 2D - Eliminada o reemplazada por 3D si se desea, pero el usuario
    pidió 3D Trophy en Hero) --}}
    {{-- <div id="floating-trophy" class="fixed right-8 bottom-8 z-50 w-24 h-24 animate-float hidden md:block">
        <img src="{{ asset('images/trophy.png') }}" alt="Trofeo" class="w-full h-full drop-shadow-2xl">
    </div> --}}

    <div class="relative min-h-screen">

        {{-- NAVBAR --}}
        <div class="fixed top-0 w-full p-6 flex justify-between items-center z-50">
            <div class="flex items-center gap-3 group cursor-pointer">
                <div
                    class="relative w-20 h-20 transition-transform duration-500 ease-out transform group-hover:scale-110">
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full opacity-0 group-hover:opacity-20 blur-xl transition-opacity duration-500">
                    </div>
                    <img src="{{ asset('images/LogoClaro.ico') }}" alt="GesPro Logo"
                        class="w-full h-full object-contain block dark:hidden animate-entrance">
                    <img src="{{ asset('images/LogoOscuro.ico') }}" alt="GesPro Logo Dark"
                        class="w-full h-full object-contain hidden dark:block animate-entrance">
                </div>
                <span class="font-bold text-4xl tracking-tight transition-colors duration-300">
                    <span class="text-purple-600">Ges</span><span class="text-gray-900 dark:text-white">Pro</span>
                </span>
            </div>

            <div class="flex items-center gap-4">
                <button id="theme-toggle-welcome" type="button"
                    class="p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg id="theme-toggle-dark-icon-welcome" class="hidden w-5 h-5" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon-welcome" class="hidden w-5 h-5" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                @if (Route::has('login'))
                    <div class="hidden sm:flex gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="font-semibold text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="font-semibold text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white transition sm: mt-1.5">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg text-sm font-bold hover:opacity-90 transition shadow-lg">Registrarse</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        {{-- HERO SECTION --}}
        <section class="min-h-screen flex items-center justify-center relative overflow-hidden">
             {{-- ELEMENTO LIBRO (Inicialmente en el fondo del Hero) --}}
            <div id="book-container" class="absolute top-1/4 left-1/2 transform -translate-x-1/2 -z-10 w-full max-w-4xl h-[500px] perspective-1000 opacity-30 blur-sm">
                
                {{-- Contraportada (Base estática derecha) --}}
                <div id="book-back-cover" class="absolute left-1/2 w-[45%] h-full bg-gradient-to-l from-blue-900 to-indigo-900 dark:from-gray-900 dark:to-black rounded-r-xl shadow-2xl border-l-2 border-gray-700 z-0"></div>

                {{-- Páginas (Stack) --}}
                <div id="book-pages-stack" class="absolute left-1/2 w-[43%] h-[96%] top-[2%] z-10 preserve-3d origin-left">
                    
                    {{-- Página 2 (Participante) --}}
                    <div id="page-2" class="book-page absolute inset-0 preserve-3d origin-left z-10">
                        {{-- Frente Página 2 (Derecha inicial) --}}
                        <div class="page-front absolute inset-0 bg-[#fdfbf7] p-8 flex flex-col justify-center backface-hidden rounded-r-lg shadow-md">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mr-4 shrink-0 shadow-lg text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">Participante</h3>
                            </div>
                            <h4 class="text-lg font-semibold text-amber-600 mb-2">Resultados Automáticos</h4>
                            <p class="text-gray-600 leading-relaxed">
                                Generación automática de rankings, podios y constancias. Acceso inmediato a tus evaluaciones y feedback.
                            </p>
                        </div>
                        {{-- Reverso Página 2 (Izquierda al girar) --}}
                        <div class="page-back absolute inset-0 bg-[#fdfbf7] p-8 flex flex-col justify-center backface-hidden rounded-l-lg shadow-md" style="transform: rotateY(180deg);">
                            <div class="text-center">
                                <h3 class="text-3xl font-bold text-gray-800 mb-4">¡Únete Ahora!</h3>
                                <p class="text-gray-600 mb-8">Forma parte de la innovación académica.</p>
                                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto text-indigo-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Página 1 (Admin/Juez) --}}
                    <div id="page-1" class="book-page absolute inset-0 preserve-3d origin-left z-20">
                        {{-- Frente Página 1 (Derecha inicial) --}}
                        <div class="page-front absolute inset-0 bg-[#fdfbf7] p-8 flex flex-col justify-center backface-hidden rounded-r-lg shadow-md">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4 shrink-0 shadow-lg text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">Administrador</h3>
                            </div>
                            <h4 class="text-lg font-semibold text-blue-600 mb-2">Equipos Dinámicos</h4>
                            <p class="text-gray-600 leading-relaxed">
                                Fomenta la colaboración creando equipos multidisciplinarios. Gestión total de usuarios, roles y eventos académicos.
                            </p>
                        </div>
                        {{-- Reverso Página 1 (Izquierda al girar) --}}
                        <div class="page-back absolute inset-0 bg-[#fdfbf7] p-8 flex flex-col justify-center backface-hidden rounded-l-lg shadow-md" style="transform: rotateY(180deg);">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mr-4 shrink-0 shadow-lg text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">Juez</h3>
                            </div>
                            <h4 class="text-lg font-semibold text-purple-600 mb-2">Evaluación Real</h4>
                            <p class="text-gray-600 leading-relaxed">
                                Sistema de rúbricas digital. Evalúa proyectos asignados y proporciona feedback instantáneo a los participantes.
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Cubierta del libro (Frente) -->
                <div id="book-cover"
                    class="absolute left-1/2 w-[45%] h-full z-30 origin-left preserve-3d">
                    
                    {{-- Frente de la portada --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-900 to-indigo-900 dark:from-gray-900 dark:to-black rounded-r-xl shadow-2xl border-l-4 border-amber-600 backface-hidden p-8">
                         <div class="h-full border-4 border-amber-500/30 rounded-lg p-6 flex flex-col justify-between">
                            <div>
                                <h2 class="text-4xl font-bold text-amber-500 mb-4 font-serif">GesPro</h2>
                                <div class="w-20 h-1 bg-amber-400 mb-6"></div>
                                <p class="text-blue-200 text-lg">Manual de Usuario</p>
                            </div>
                            <div class="text-right">
                                <p class="text-white text-2xl font-semibold">2025</p>
                            </div>
                        </div>
                    </div>

                    {{-- Reverso de la portada (Visible al abrir) --}}
                    <div class="absolute inset-0 bg-blue-900 rounded-l-xl shadow-inner backface-hidden p-8 flex flex-col justify-center" style="transform: rotateY(180deg);">
                        <h3 class="text-2xl font-bold text-white mb-4 text-center">Bienvenido</h3>
                        <p class="text-blue-100 text-center">Explora los módulos del sistema.</p>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-6 pt-20 pb-16 lg:pt-32 relative z-10 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    {{-- Contenido principal --}}
                    <div class="text-center lg:text-left">
                        <h1
                            class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 text-gray-900 dark:text-white">
                            Gestión de <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">Proyectos</span>
                        </h1>

                        <p class="max-w-2xl text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-10 leading-relaxed">
                            Centraliza eventos, forma equipos multidisciplinarios y evalúa resultados en tiempo real. La
                            herramienta definitiva para docentes y estudiantes.
                        </p>

                        <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}"
                                    class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-lg shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all duration-300">
                                    Ir al Panel
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-lg shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all duration-300">
                                    Comenzar Ahora
                                </a>
                                <a href="{{ route('register') }}"
                                    class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl font-bold text-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:-translate-y-1 transition-all duration-300">
                                    Crear Cuenta
                                </a>
                            @endauth
                        </div>
                    </div>

                    {{-- Contenedor del modelo 3D del Trofeo --}}
                    <div class="relative h-[500px] w-full hidden lg:block perspective-1000 z-20">
                        {{-- Canvas flotante sin contenedor visible --}}
                        <canvas id="trophy-canvas" class="w-full h-full object-contain drop-shadow-2xl"></canvas>

                        {{-- Elementos decorativos flotantes --}}
                        <div
                            class="absolute top-1/4 right-10 w-20 h-20 bg-yellow-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob">
                        </div>
                        <div
                            class="absolute bottom-1/4 left-10 w-20 h-20 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECCIÓN DEL LIBRO ANIMADO (Placeholder para el scroll) --}}
        <section id="book-section" class="min-h-screen py-20 flex items-center justify-center relative">
            <div class="max-w-7xl mx-auto px-6 w-full h-full flex flex-col items-center justify-center">
                <div class="text-center mb-16 opacity-0" id="book-text">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                        Descubre Nuestro Sistema
                    </h2>
                </div>

                {{-- El libro se moverá aquí visualmente --}}
                <div class="h-[600px] w-full"></div>

                <!-- Controles del libro -->
                <div class="text-center mt-12 opacity-0" id="book-controls">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Desplázate para pasar página
                    </p>
                    <div
                        class="w-24 h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent mx-auto rounded-full">
                    </div>
                </div>
            </div>
        </section>



        <footer
            class="py-8 text-center w-full text-sm text-gray-400 dark:text-gray-600 border-t border-gray-100 dark:border-gray-800">
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

        welcomeToggleBtn.addEventListener('click', function () {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            updateIcons();
        });
    </script>


</body>

</html>