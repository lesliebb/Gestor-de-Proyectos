<x-guest-layout>
    {{-- Contenedor Principal con efecto de borde brillante --}}
    <div class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Glow Effect --}}
        <div
            class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-3xl blur opacity-25 animate-pulse">
        </div>

        <div
            class="relative flex flex-col md:flex-row h-full overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 auth-card opacity-0 transform scale-95">

            {{-- BOTÓN TOGGLE TEMA (Ubicado a la Izquierda en esta vista para variar o derecha) --}}
            <button id="theme-toggle-register" type="button"
                class="absolute top-4 right-4 z-50 p-2 rounded-full text-white/80 hover:text-white md:text-gray-500 md:hover:bg-gray-100 md:dark:text-gray-400 md:dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg id="theme-toggle-dark-icon-reg" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="theme-toggle-light-icon-reg" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                        fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>

            {{-- SECCIÓN FORMULARIO (Izquierda en Registro) --}}
            <div
                class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center order-2 md:order-1 bg-white dark:bg-gray-800">

                <div class="mb-8 auth-header">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Crear Cuenta</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Únete a la plataforma para participar.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5 auth-form">
                    @csrf

                    <div class="form-item">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre
                            Completo</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                autocomplete="name"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                                placeholder="Juan Pérez" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo letras y espacios">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

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
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                                placeholder="tu@email.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="form-item">
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                                placeholder="Mínimo 8 caracteres">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="form-item">
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar
                            Contraseña</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
                                placeholder="Repite tu contraseña">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="pt-2 form-item">
                        <button type="submit"
                            class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg hover:shadow-indigo-500/30 transition duration-200 transform hover:-translate-y-0.5">
                            {{ __('Registrarse') }}
                        </button>
                    </div>

                    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400 form-item">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('login') }}"
                            class="font-bold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 transition-colors">
                            Inicia Sesión
                        </a>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN VISUAL (Derecha en Registro) --}}
            <div
                class="hidden md:flex md:w-1/2 bg-gradient-to-bl from-purple-900 via-indigo-900 to-black p-12 flex-col justify-between text-white relative order-1 md:order-2 overflow-hidden">
                <div
                    class="absolute top-0 left-0 -ml-10 -mt-10 w-40 h-40 bg-indigo-500 opacity-20 rounded-full blur-3xl animate-blob">
                </div>
                <div
                    class="absolute bottom-0 right-0 -mr-10 -mb-10 w-60 h-60 bg-purple-500 opacity-20 rounded-full blur-3xl animate-blob animation-delay-2000">
                </div>

                <div class="relative z-10 text-right">
                    <div class="flex items-center justify-end gap-3 mb-6">
                        <span class="font-bold text-4xl tracking-tight">
                            <span class="text-white">Ges</span><span class="text-indigo-400">Pro</span>
                        </span>
                        <img src="{{ asset('images/LogoClaro.ico') }}" alt="GesPro Logo"
                            class="w-16 h-16 object-contain block dark:hidden">
                        <img src="{{ asset('images/LogoOscuro.ico') }}" alt="GesPro Logo Dark"
                            class="w-16 h-16 object-contain hidden dark:block">
                    </div>
                </div>

                <div class="relative z-10 text-center mb-10">
                    <h2 class="text-3xl font-extrabold mb-4">¡Únete a la Innovación!</h2>
                    <p class="text-indigo-100 text-lg leading-relaxed mb-8">
                        Crea tu perfil para registrar equipos, subir proyectos y participar en los mejores eventos
                        académicos.
                    </p>
                </div>

                <div class="text-xs text-indigo-200 opacity-70 text-center">
                    &copy; {{ date('Y') }} GesPro System.
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPT PARA MANEJAR EL MODO OBSCURO EN ESTA VISTA --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        const regToggleBtn = document.getElementById('theme-toggle-register');
        const regDarkIcon = document.getElementById('theme-toggle-dark-icon-reg');
        const regLightIcon = document.getElementById('theme-toggle-light-icon-reg');

        function updateRegIcons() {
            if (document.documentElement.classList.contains('dark')) {
                regLightIcon.classList.remove('hidden');
                regDarkIcon.classList.add('hidden');
            } else {
                regLightIcon.classList.add('hidden');
                regDarkIcon.classList.remove('hidden');
            }
        }
        updateRegIcons();

        regToggleBtn.addEventListener('click', function () {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            updateRegIcons();
        });
    </script>
</x-guest-layout>