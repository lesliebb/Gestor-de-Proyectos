<x-guest-layout>
    {{-- Contenedor Principal con efecto de borde brillante --}}
    <div class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Glow Effect --}}
        <div
            class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-3xl blur opacity-25 animate-pulse">
        </div>

        <div
            class="relative flex flex-col md:flex-row h-full overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 auth-card opacity-0 transform scale-95">

            {{-- BOTÓN TOGGLE TEMA (Ubicado en la esquina superior derecha de la tarjeta) --}}
            <button id="theme-toggle-login" type="button"
                class="absolute top-4 right-4 z-50 p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg id="theme-toggle-dark-icon-login" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="theme-toggle-light-icon-login" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                        fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>

            {{-- SECCIÓN VISUAL (Izquierda) --}}
            <div
                class="hidden md:flex md:w-1/2 bg-gradient-to-br from-indigo-900 via-purple-900 to-black p-12 flex-col justify-between text-white relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-indigo-500 opacity-20 rounded-full blur-3xl animate-blob">
                </div>
                <div
                    class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-purple-500 opacity-20 rounded-full blur-3xl animate-blob animation-delay-2000">
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('images/LogoClaro.ico') }}" alt="GesPro Logo"
                            class="w-16 h-16 object-contain block dark:hidden">
                        <img src="{{ asset('images/LogoOscuro.ico') }}" alt="GesPro Logo Dark"
                            class="w-16 h-16 object-contain hidden dark:block">
                        <span class="font-bold text-4xl tracking-tight">
                            <span class="text-white">Ges</span><span class="text-indigo-400">Pro</span>
                        </span>
                    </div>
                    <p class="mt-2 text-indigo-100 opacity-90 text-lg">Gestión de Proyectos Académicos</p>
                </div>

                <div class="relative z-10 mb-10">
                    <h2 class="text-3xl font-bold mb-4">¡Bienvenido de nuevo!</h2>
                    <p class="text-indigo-100 leading-relaxed opacity-90">
                        Ingresa a tu cuenta para continuar gestionando equipos, evaluando proyectos y visualizando
                        resultados.
                    </p>
                </div>

                <div class="text-xs text-indigo-200 opacity-70">
                    &copy; {{ date('Y') }} GesPro System.
                </div>
            </div>

            {{-- SECCIÓN FORMULARIO (Derecha) --}}
            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white dark:bg-gray-800">

                <div class="text-center md:text-left mb-8 auth-header">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Iniciar Sesión</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Por favor, introduce tus credenciales.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5 auth-form">
                    @csrf

                    <div class="form-item">
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo
                            Electrónico</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                                placeholder="nombre@ejemplo.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div x-data="{ show: false }" class="form-item">
                        <div class="flex justify-between items-center mb-1">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
                                    href="{{ route('password.request') }}">
                                    {{ __('¿Olvidaste tu contraseña?') }}
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                autocomplete="current-password"
                                class="w-full pl-10 pr-10 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 013.949-5.347m1.735-1.277L21 21m0 0l-1.735-1.277M10.723 8.723a3 3 0 014.554 4.554" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="block form-item">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:focus:ring-offset-gray-800"
                                name="remember">
                            <span
                                class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Mantener sesión iniciada') }}</span>
                        </label>
                    </div>

                    <div class="pt-2 form-item">
                        <button type="submit"
                            class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg hover:shadow-indigo-500/30 transition duration-200 transform hover:-translate-y-0.5">
                            {{ __('Ingresar') }}
                        </button>
                    </div>

                    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400 form-item">
                        ¿No tienes una cuenta?
                        <a href="{{ route('register') }}"
                            class="font-bold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 transition-colors">
                            Regístrate aquí
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT PARA MANEJAR EL MODO OBSCURO EN ESTA VISTA --}}
    <script>
        // 1. Aplicar tema al cargar (para evitar parpadeo si guest layout no lo hace)
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // 2. Lógica del Botón
        const loginToggleBtn = document.getElementById('theme-toggle-login');
        const loginDarkIcon = document.getElementById('theme-toggle-dark-icon-login');
        const loginLightIcon = document.getElementById('theme-toggle-light-icon-login');

        function updateLoginIcons() {
            if (document.documentElement.classList.contains('dark')) {
                loginLightIcon.classList.remove('hidden');
                loginDarkIcon.classList.add('hidden');
            } else {
                loginLightIcon.classList.add('hidden');
                loginDarkIcon.classList.remove('hidden');
            }
        }
        updateLoginIcons();

        loginToggleBtn.addEventListener('click', function () {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            updateLoginIcons();
        });
    </script>
</x-guest-layout>