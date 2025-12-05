<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $evento->nombre }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Panel de Gestión y Evaluación</p>
            </div>
            <a href="{{ route('juez.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{ tab: 'equipos' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- TABS DE NAVEGACIÓN --}}
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="tab = 'equipos'"
                        :class="tab === 'equipos' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Proyectos a Evaluar
                    </button>

                    <button @click="tab = 'criterios'"
                        :class="tab === 'criterios' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Configuración de Rúbrica
                    </button>
                </nav>
            </div>

            {{-- CONTENIDO TAB 1: LISTA DE PROYECTOS --}}
            <div x-show="tab === 'equipos'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Listado de Proyectos</h3>
                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full">{{ $evento->proyectos->count() }} Total</span>
                    </div>

                    @php
                        // Ordenamiento por puntuación descendente
                        $proyectosOrdenados = $evento->proyectos->sortByDesc(function ($p) {
                            return $p->calificaciones->sum('puntuacion');
                        });
                    @endphp

                    @if ($proyectosOrdenados->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-full mb-3">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <h3 class="text-gray-900 dark:text-white font-medium">No hay proyectos registrados</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Los equipos aparecerán aquí cuando se inscriban.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wider">
                                        <th class="px-6 py-4 text-center w-24">Ranking</th>
                                        <th class="px-6 py-4">Equipo / Integrantes</th>
                                        <th class="px-6 py-4">Proyecto</th>
                                        <th class="px-6 py-4 text-center">Estado</th>
                                        <th class="px-6 py-4 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($proyectosOrdenados as $proyecto)
                                        @php
                                            $equipo = $proyecto->equipo;
                                            $yaCalificado = $proyecto->calificaciones->where('juez_user_id', auth()->id())->isNotEmpty();
                                            $rank = $loop->iteration;
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors group">
                                            
                                            {{-- COLUMNA RANKING CON SVGs --}}
                                            <td class="px-6 py-4 align-middle text-center">
                                                <div class="flex justify-center items-center h-16 w-16 mx-auto relative group-hover:scale-110 transition-transform duration-300">
                                                    
                                                    @if($rank === 1)
                                                        {{-- MEDALLA DE ORO (SVG Proporcionado) --}}
                                                        <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 drop-shadow-[0_0_15px_rgba(252,205,29,0.7)]">
                                                            <g id="Flat">
                                                                <g id="Color">
                                                                    <polygon fill="#212529" points="8.26 3 25.94 33.62 38.06 26.62 24.42 3 8.26 3"/><path d="M38.06,26.62l-7.21-12.5-3.72,6.44a21.53,21.53,0,0,0-7,3l5.8,10Z" fill="#111315"/><polygon fill="#dd051d" points="34.6 28.62 29.4 31.62 12.87 3 19.8 3 34.6 28.62"/><polygon fill="#212529" points="39.58 3 25.94 26.62 38.06 33.62 55.74 3 39.58 3"/><path d="M34.6,28.62l-6.06-10.5-1.42,2.46a21.44,21.44,0,0,0-3.46,1.1l5.74,9.94Z" fill="#a60416"/><path d="M43.86,23.58a21.46,21.46,0,0,0-14.17-3.45l-3.75,6.49,12.12,7Z" fill="#111315"/><polygon fill="#dd051d" points="51.13 3 34.6 31.62 29.4 28.62 44.2 3 51.13 3"/><path d="M34.6,31.62l5.74-9.94a21.41,21.41,0,0,0-6-1.55L29.4,28.62Z" fill="#a60416"/>
                                                                    {{-- Circulo Dorado --}}
                                                                    <circle cx="32" cy="41.5" fill="#fccd1d" r="19.5"/><circle cx="32" cy="41.5" fill="#f9a215" r="14.5"/>
                                                                    {{-- Número 1 --}}
                                                                    <path d="M34.13,43.63V33H29.88a3.19,3.19,0,0,1-3.19,3.19H25.63v4.25h4.25v3.19a2.13,2.13,0,0,1-2.13,2.12H25.63V50H38.38V45.75H36.25A2.12,2.12,0,0,1,34.13,43.63Z" fill="#fccd1d"/>
                                                                </g>
                                                            </g>
                                                        </svg>

                                                    @elseif($rank === 2)
                                                        {{-- MEDALLA DE PLATA (SVG Modificado a tonos grises/plata) --}}
                                                        <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 drop-shadow-[0_0_15px_rgba(200,200,200,0.6)]">
                                                            <g id="Flat">
                                                                <g id="Color">
                                                                    <polygon fill="#212529" points="8.26 3 25.94 33.62 38.06 26.62 24.42 3 8.26 3"/><path d="M38.06,26.62l-7.21-12.5-3.72,6.44a21.53,21.53,0,0,0-7,3l5.8,10Z" fill="#111315"/><polygon fill="#dd051d" points="34.6 28.62 29.4 31.62 12.87 3 19.8 3 34.6 28.62"/><polygon fill="#212529" points="39.58 3 25.94 26.62 38.06 33.62 55.74 3 39.58 3"/><path d="M34.6,28.62l-6.06-10.5-1.42,2.46a21.44,21.44,0,0,0-3.46,1.1l5.74,9.94Z" fill="#a60416"/><path d="M43.86,23.58a21.46,21.46,0,0,0-14.17-3.45l-3.75,6.49,12.12,7Z" fill="#111315"/><polygon fill="#dd051d" points="51.13 3 34.6 31.62 29.4 28.62 44.2 3 51.13 3"/><path d="M34.6,31.62l5.74-9.94a21.41,21.41,0,0,0-6-1.55L29.4,28.62Z" fill="#a60416"/>
                                                                    {{-- Circulo Plata (Colores ajustados) --}}
                                                                    <circle cx="32" cy="41.5" fill="#E2E8F0" r="19.5"/><circle cx="32" cy="41.5" fill="#94A3B8" r="14.5"/>
                                                                    {{-- Número 2 --}}
                                                                    <path d="M33.88,33.57a6.49,6.49,0,0,0-5.81,1.23,6.41,6.41,0,0,0-2.21,4.89H30c0-2.24,3.37-2.38,4-1,1,2.1-8,7-8,7v4H38v-4H34a7.07,7.07,0,0,0,4-7.54A6.16,6.16,0,0,0,33.88,33.57Z" fill="#E2E8F0"/>
                                                                </g>
                                                            </g>
                                                        </svg>

                                                    @elseif($rank === 3)
                                                        {{-- MEDALLA DE BRONCE (SVG Modificado a tonos bronce/cobre) --}}
                                                        <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 drop-shadow-[0_0_15px_rgba(205,127,50,0.6)]">
                                                            <g id="Flat">
                                                                <g id="Color">
                                                                    <polygon fill="#212529" points="8.26 3 25.94 33.62 38.06 26.62 24.42 3 8.26 3"/><path d="M38.06,26.62l-7.21-12.5-3.72,6.44a21.53,21.53,0,0,0-7,3l5.8,10Z" fill="#111315"/><polygon fill="#dd051d" points="34.6 28.62 29.4 31.62 12.87 3 19.8 3 34.6 28.62"/><polygon fill="#212529" points="39.58 3 25.94 26.62 38.06 33.62 55.74 3 39.58 3"/><path d="M34.6,28.62l-6.06-10.5-1.42,2.46a21.44,21.44,0,0,0-3.46,1.1l5.74,9.94Z" fill="#a60416"/><path d="M43.86,23.58a21.46,21.46,0,0,0-14.17-3.45l-3.75,6.49,12.12,7Z" fill="#111315"/><polygon fill="#dd051d" points="51.13 3 34.6 31.62 29.4 28.62 44.2 3 51.13 3"/><path d="M34.6,31.62l5.74-9.94a21.41,21.41,0,0,0-6-1.55L29.4,28.62Z" fill="#a60416"/>
                                                                    {{-- Circulo Bronce (Colores ajustados) --}}
                                                                    <circle cx="32" cy="41.5" fill="#FDBA74" r="19.5"/><circle cx="32" cy="41.5" fill="#C2410C" r="14.5"/>
                                                                    {{-- Número 3 --}}
                                                                    <path d="M36.54,41.5A4.52,4.52,0,0,0,38.38,38c0-2.76-2.86-5-6.38-5s-6.37,2.24-6.37,5h3.92a2,2,0,0,1,3.9-.29c.17,1.23-.77,2.73-2,2.73v2.12c2.22,0,2.84,3.5.72,4.32A2,2,0,0,1,29.55,45H25.63c0,2.76,2.85,5,6.37,5s6.38-2.24,6.38-5A4.52,4.52,0,0,0,36.54,41.5Z" fill="#FDBA74"/>
                                                                </g>
                                                            </g>
                                                        </svg>

                                                    @else
                                                        {{-- Ranking normal para 4to lugar en adelante --}}
                                                        <div class="w-10 h-10 flex items-center justify-center rounded-full font-bold text-sm bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                                            {{ $rank }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Equipo --}}
                                            <td class="px-6 py-4 align-top">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                                        {{ substr($equipo->nombre ?? 'S', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $equipo->nombre ?? 'Sin Equipo' }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                            {{ $equipo ? $equipo->participantes->count() : 0 }} Miembros
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Proyecto --}}
                                            <td class="px-6 py-4 align-top">
                                                <p class="font-bold text-gray-800 dark:text-gray-200 text-sm mb-1">{{ $proyecto->nombre }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 max-w-xs leading-relaxed">
                                                    {{ $proyecto->descripcion }}
                                                </p>
                                            </td>

                                            {{-- Estado --}}
                                            <td class="px-6 py-4 align-top text-center">
                                                @if ($yaCalificado)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        Evaluado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Pendiente
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Acciones --}}
                                            <td class="px-6 py-4 align-top text-right">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('juez.evaluaciones.edit', $proyecto) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors duration-200">
                                                        {{ $yaCalificado ? 'Editar Nota' : 'Evaluar' }}
                                                    </a>
                                                    
                                                    <a href="{{ route('juez.equipos.edit', $equipo) }}" 
                                                       class="inline-flex items-center p-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" title="Gestionar Equipo">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CONTENIDO TAB 2: RÚBRICA (CRITERIOS) --}}
            <div x-show="tab === 'criterios'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 x-data="{ editing: null, editForm: { id: null, nombre: '', ponderacion: '' } }">

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-700 dark:text-red-300 text-sm font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 text-green-700 dark:text-green-300 text-sm font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {{-- Columna Izquierda: Formulario (4 cols) --}}
                    <div class="lg:col-span-4">
                        <div class="bg-white dark:bg-gray-800 shadow-lg shadow-gray-200/50 dark:shadow-none rounded-2xl p-6 border border-gray-100 dark:border-gray-700 sticky top-8">
                            
                            @php
                                $sumaTotal = $evento->criterios->sum('ponderacion');
                                $disponible = 100 - $sumaTotal;
                            @endphp

                            <div class="mb-8 text-center">
                                <div class="relative w-32 h-32 mx-auto mb-3">
                                    <svg class="w-full h-full transform -rotate-90">
                                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="transparent" class="text-gray-100 dark:text-gray-700" />
                                        <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="transparent" 
                                                :stroke-dasharray="351.86" 
                                                :stroke-dashoffset="351.86 - (351.86 * {{ $sumaTotal }} / 100)"
                                                class="{{ $disponible == 0 ? 'text-green-500' : 'text-indigo-600' }} transition-all duration-1000 ease-out" />
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $disponible }}%</span>
                                        <span class="text-[10px] text-gray-500 uppercase font-bold">Libre</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $disponible == 0 ? '¡Rúbrica Completada!' : 'Agrega criterios hasta completar el 100%.' }}
                                </p>
                            </div>

                            <form action="{{ route('juez.criterios.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="evento_id" value="{{ $evento->id }}">

                                <div class="space-y-4">
                                    <div>
                                        <label for="nombre" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Nombre del Criterio</label>
                                        <input type="text" id="nombre" name="nombre" placeholder="Ej: Innovación Tecnológica" required
                                            class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    </div>

                                    <div>
                                        <label for="ponderacion" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Valor (%)</label>
                                        <div class="relative">
                                            <input type="number" id="ponderacion" name="ponderacion" min="1" max="{{ $disponible }}" required
                                                class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-sm pr-8">
                                            <span class="absolute right-3 top-2 text-gray-400 font-bold text-sm">%</span>
                                        </div>
                                    </div>

                                    <button type="submit" 
                                            class="w-full py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                                            {{ $disponible <= 0 ? 'disabled' : '' }}>
                                        {{ $disponible <= 0 ? 'Completo' : 'Añadir Criterio' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Columna Derecha: Lista (8 cols) --}}
                    <div class="lg:col-span-8">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <h3 class="font-bold text-gray-900 dark:text-white">Criterios Definidos</h3>
                                <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-3 py-1 rounded-full text-xs font-bold">{{ $evento->criterios->count() }} Criterios</span>
                            </div>

                            @if ($evento->criterios->isEmpty())
                                <div class="text-center py-12">
                                    <p class="text-gray-400 text-sm">No has definido criterios para este evento.</p>
                                </div>
                            @else
                                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($evento->criterios as $criterio)
                                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors flex items-center justify-between group">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-800 dark:text-white font-black text-lg shadow-inner">
                                                    {{ $criterio->ponderacion }}<span class="text-[10px] align-top">%</span>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $criterio->nombre }}</h4>
                                                    <div class="w-32 h-1.5 bg-gray-200 dark:bg-gray-600 rounded-full mt-2 overflow-hidden">
                                                        <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $criterio->ponderacion }}%"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button @click="editing = true; editForm = { id: {{ $criterio->id }}, nombre: '{{ $criterio->nombre }}', ponderacion: {{ $criterio->ponderacion }} }"
                                                    class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                
                                                <form action="{{ route('juez.criterios.destroy', $criterio->id) }}" method="POST" onsubmit="return confirm('¿Eliminar criterio?');">
                                                    @csrf @method('DELETE')
                                                    <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- MODAL DE EDICIÓN --}}
                <div x-show="editing" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="editing = false">
                            <div class="absolute inset-0 bg-gray-900 opacity-75 backdrop-blur-sm"></div>
                        </div>

                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white mb-4">
                                    Editar Criterio
                                </h3>
                                <form :action="'/juez/criterios/' + editForm.id" method="POST">
                                    @csrf @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                                            <input type="text" name="nombre" x-model="editForm.nombre" required
                                                class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Ponderación (%)</label>
                                            <input type="number" name="ponderacion" x-model="editForm.ponderacion" min="1" max="100" required
                                                class="w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>
                                    <div class="mt-6 flex justify-end gap-3">
                                        <button type="button" @click="editing = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Guardar Cambios
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>