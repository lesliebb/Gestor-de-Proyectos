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
                <span
                    class="font-bold text-2xl tracking-tight dark:text-white group-hover:text-indigo-500 transition-colors duration-300">GesPro</span>
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
        <section class="min-h-screen flex items-center justify-center">
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
                    <div class="relative h-[500px] w-full hidden lg:block perspective-1000">
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

        {{-- SECCIÓN DEL LIBRO ANIMADO --}}
        <section id="book-section" class="min-h-screen py-20 flex items-center justify-center relative">
            <div class="max-w-7xl mx-auto px-6 w-full">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                        Descubre Nuestro Sistema
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        Explora las funcionalidades de GesPro a través de esta experiencia interactiva
                    </p>
                </div>

                <!-- Contenedor del libro -->
                <div id="book-container" class="relative w-full max-w-6xl h-[600px] perspective-1000 mx-auto">
                    <!-- Cubierta del libro -->
                    <div id="book-cover"
                        class="absolute w-1/2 h-full bg-gradient-to-r from-blue-800 to-indigo-900 dark:from-gray-800 dark:to-gray-900 rounded-l-xl shadow-2xl border-r-8 border-amber-600 z-20 origin-right preserve-3d">
                        <!-- Diseño de portada -->
                        <div class="absolute inset-0 p-8">
                            <div
                                class="h-full border-4 border-amber-500/30 rounded-lg p-6 flex flex-col justify-between">
                                <div>
                                    <h2 class="text-3xl font-bold text-white mb-4">GesPro System</h2>
                                    <div class="w-20 h-1 bg-amber-400 mb-6"></div>
                                    <p class="text-blue-200 text-lg">Edición 2025</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-white text-2xl font-semibold">v0.9.0</p>
                                    <p class="text-blue-200">Sistema Integral</p>
                                </div>
                            </div>
                        </div>

                        <!-- Lomo del libro -->
                        <div
                            class="absolute -right-8 top-4 w-8 h-[calc(100%-2rem)] bg-gradient-to-b from-amber-800 to-amber-900 rounded-r-lg">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div
                                    class="transform -rotate-90 text-white font-bold tracking-widest whitespace-nowrap">
                                    GESTIÓN ACADÉMICA
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Páginas del libro -->
                    <div id="book-pages"
                        class="absolute left-1/2 w-1/2 h-full bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-gray-800 dark:to-gray-700 rounded-r-xl shadow-2xl z-10 preserve-3d overflow-hidden paper-texture book-thickness">

                        <!-- Contenedor relativo para los slides -->
                        <div class="relative w-full h-full">

                            <!-- Slide 1: Administrador -->
                            <div
                                class="book-slide absolute inset-0 p-8 flex flex-col justify-center opacity-100 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-gray-800 dark:to-gray-700">
                                <div class="flex items-center mb-6">
                                    <div
                                        class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4 shrink-0 shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Módulo Administrador
                                    </h3>
                                </div>
                                <ul class="space-y-4 text-gray-700 dark:text-gray-300">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-1 shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Gestión total de usuarios y roles.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-1 shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Control de eventos y categorías.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-1 shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Supervisión de evaluaciones.</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Slide 2: Juez -->
                            <div
                                class="book-slide absolute inset-0 p-8 flex flex-col justify-center opacity-0 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-gray-800 dark:to-gray-700">
                                <div class="flex items-center mb-6">
                                    <div
                                        class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mr-4 shrink-0 shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Módulo Juez</h3>
                                </div>
                                <ul class="space-y-4 text-gray-700 dark:text-gray-300">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-1 shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Evaluación de proyectos asignados.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-1 shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Rúbricas dinámicas y claras.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-1 shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Feedback en tiempo real.</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Slide 3: Participante -->
                            <div
                                class="book-slide absolute inset-0 p-8 flex flex-col justify-center opacity-0 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-gray-800 dark:to-gray-700">
                                <div class="flex items-center mb-6">
                                    <div
                                        class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mr-4 shrink-0 shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Módulo Participante
                                    </h3>
                                </div>
                                <ul class="space-y-4 text-gray-700 dark:text-gray-300">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-1 shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Registro de equipos y proyectos.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-1 shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Seguimiento de evaluaciones.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-1 shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Acceso a resultados.</span>
                                    </li>
                                </ul>
                            </div>

                        </div>

                        <!-- Separador de páginas -->
                        <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-amber-200 to-amber-400 z-20">
                        </div>
                    </div>
                </div>

                <!-- Controles del libro -->
                <div class="text-center mt-12">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Desplázate para abrir el libro
                    </p>
                    <div
                        class="w-24 h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent mx-auto rounded-full">
                    </div>
                </div>
            </div>
        </section>

        {{-- FEATURES GRID --}}
        <section class="py-20">
            <div class="max-w-7xl mx-auto px-6 pb-20 w-full">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    <div
                        class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:border-indigo-500/30 dark:hover:border-indigo-500/30 hover:-translate-y-2 transition-all duration-300">
                        <div
                            class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Equipos Dinámicos</h3>
                        <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                            Fomenta la colaboración. Crea equipos multidisciplinarios combinando talento de diversas
                            carreras.
                        </p>
                    </div>

                    <div
                        class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:border-purple-500/30 dark:hover:border-purple-500/30 hover:-translate-y-2 transition-all duration-300">
                        <div
                            class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center text-purple-600 dark:text-purple-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Evaluación en Tiempo Real</h3>
                        <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                            Sistema de rúbricas digital. Los jueces evalúan y los resultados se calculan
                            instantáneamente.
                        </p>
                    </div>

                    <div
                        class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:border-pink-500/30 dark:hover:border-pink-500/30 hover:-translate-y-2 transition-all duration-300">
                        <div
                            class="w-14 h-14 bg-pink-100 dark:bg-pink-900/30 rounded-2xl flex items-center justify-center text-pink-600 dark:text-pink-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Resultados Automáticos</h3>
                        <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                            Generación automática de rankings, podios y constancias de participación listas para
                            descargar.
                        </p>
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