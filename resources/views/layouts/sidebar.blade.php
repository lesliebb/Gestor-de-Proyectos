<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="absolute left-0 top-0 z-50 flex h-screen w-72.5 flex-col overflow-y-hidden bg-white dark:bg-gray-900 duration-300 ease-linear lg:static lg:translate-x-0 border-r border-gray-200 dark:border-gray-800">

    <!-- HEADER DEL SIDEBAR (Logo) -->
    <div
        class="flex items-center justify-between gap-2 pl-3 py-3 h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <a href="{{ route(Auth::user()->getDashboardRouteName()) }}" class="flex items-center gap-2">
            {{-- Logo: Cambia el fill a índigo o el color de tu marca en modo claro --}}
            <x-application-logo class="w-16 h-16 fill-current text-indigo-600 dark:text-white" />
            <span class="text-xl font-bold text-gray-800 dark:text-white tracking-wider">GesPro</span>
        </a>

        <button @click.stop="sidebarOpen = !sidebarOpen" class="block lg:hidden text-gray-500 dark:text-gray-400">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
        <nav class="mt-5 py-4 px-4 lg:mt-9 lg:px-6">
            <div>
                <h3 class="mb-4 ml-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Menú Principal</h3>

                <ul class="mb-6 flex flex-col gap-1.5">

                    {{-- 1. Dashboard (Común) --}}
                    <li>
                        <a href="{{ route(Auth::user()->getDashboardRouteName()) }}"
                            class="group relative flex items-center gap-2.5 rounded-xl py-2 px-4 font-medium duration-300 ease-in-out 
                           {{ request()->routeIs('*.dashboard')
                               ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-800 dark:text-white'
                               : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    {{-- Enlaces Admin con clases condicionales limpias --}}
                    @php
                        // CAMBIO: rounded-xl para bordes más redondos
                        $linkClass =
                            'group relative flex items-center gap-2.5 rounded-xl py-2 px-4 font-medium duration-300 ease-in-out text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white';
                        $activeClass = 'bg-indigo-50 text-indigo-600 dark:bg-gray-800 dark:text-white';
                    @endphp

                    {{-- 2. MENÚ ADMIN --}}
                    @if (Auth::user()->roles->contains('nombre', 'Admin'))
                        <li>
                            <p class="mt-4 mb-2 ml-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase">
                                Administración</p>
                        </li>
                        <li>
                            <a href="{{ route('admin.usuarios.index') }}"
                                class="{{ $linkClass }} {{ request()->routeIs('admin.usuarios.*') ? $activeClass : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                Usuarios
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.eventos.index') }}"
                                class="{{ $linkClass }} {{ request()->routeIs('admin.eventos.*') ? $activeClass : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Eventos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.equipos.index') }}"
                                class="{{ $linkClass }} {{ request()->routeIs('admin.equipos.*') ? $activeClass : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Equipos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.proyectos.index') }}"
                                class="{{ $linkClass }} {{ request()->routeIs('admin.proyectos.*') ? $activeClass : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                                Proyectos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.resultados.index') }}"
                                class="{{ $linkClass }} {{ request()->routeIs('admin.resultados.*') ? $activeClass : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Resultados
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.carreras.index') }}"
                                class="{{ $linkClass }} {{ request()->routeIs('admin.carreras.*') ? $activeClass : '' }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                </svg>
                                Carreras
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.perfiles.index') }}"
                                class="{{ $linkClass }} {{ request()->routeIs('admin.perfiles.*') ? $activeClass : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.5 2-2 2h4c-1.5 0-2-1.116-2-2z" />
                                </svg>
                                Perfiles
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reportes.index') }}"
                                class="{{ $linkClass }} {{ request()->routeIs('admin.reportes.*') ? $activeClass : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Reportes
                            </a>
                        </li>
                    @endif

                    {{-- 3. MENÚ JUEZ --}}
                    @if (Auth::user()->roles->contains('nombre', 'Juez'))
                        <li>
                            <p class="mt-4 mb-2 ml-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase">
                                Juez</p>
                        </li>
                        <li>
                            <a href="{{ route('juez.dashboard') }}"
                                class="{{ $linkClass }} {{ request()->routeIs('juez.dashboard') ? $activeClass : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Sala de Evaluación
                            </a>
                        </li>
                    @endif

                    {{-- 4. MENÚ PARTICIPANTE --}}
                    @if (Auth::user()->roles->contains('nombre', 'Participante'))
                        <li>
                            <p class="mt-4 mb-2 ml-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase">
                                Participación</p>
                        </li>
                        <li>
                            <a href="{{ route('participante.dashboard') }}"
                                class="{{ $linkClass }} {{ request()->routeIs('participante.dashboard') ? $activeClass : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Mi Panel
                            </a>
                        </li>
                    @endif

                </ul>
            </div>
        </nav>
    </div>
</aside>
