<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel del Participante') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($equipo)
                {{-- ... (El resto de la vista cuando el participante TIENE equipo) ... --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- ================================================= --}}
                    {{--               COLUMNA IZQUIERDA (2/3)             --}}
                    {{-- ================================================= --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- 1. FILA SUPERIOR: ESTADO Y EVENTO ACTUAL --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Estado Actual</p>
                                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">Inscrito</h4>
                                </div>
                                <div
                                    class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>

                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Evento Activo</p>
                                    <h4 class="text-sm font-bold text-gray-800 dark:text-white mt-1 truncate max-w-[150px]"
                                        title="{{ $evento_inscrito->nombre }}">
                                        {{ $evento_inscrito->nombre ?? 'Ninguno' }}
                                    </h4>
                                </div>
                                <div
                                    class="p-3 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- 2. CARD PRINCIPAL DEL EQUIPO Y PROYECTO --}}
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 flex flex-wrap gap-4 justify-between items-center">
                                <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    {{ $equipo->nombre }}
                                </h3>

                                {{-- ACCIONES (Botones) --}}
                                @php
                                    $mi_participacion = $equipo->participantes->where('user_id', Auth::id())->first();
                                    $es_lider = false;
                                    if ($mi_participacion) {
                                        // Ajusta el ID 3 si tu lógica cambió, o usa la columna 'es_lider' si la agregaste
                                        $es_lider =
                                            $mi_participacion->pivot->perfil_id == 3 ||
                                            $mi_participacion->pivot->es_lider;
                                    }
                                @endphp

                                <div class="flex items-center gap-2">
                                    @if ($es_lider)
                                        <a href="{{ route('participante.equipos.edit', $equipo) }}"
                                            class="text-xs px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 transition font-medium">
                                            Gestionar
                                        </a>
                                    @endif

                                    <a href="{{ route('participante.avances.index') }}"
                                        class="text-xs px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition font-medium shadow-sm">
                                        Subir Avances
                                    </a>

                                    <form action="{{ route('participante.equipos.leave') }}" method="POST"
                                        onsubmit="return confirm('¿Estás seguro de abandonar el equipo? Si eres el único líder, no podrás hacerlo.');">
                                        @csrf
                                        @method('DELETE') <button type="submit"
                                            class="text-xs px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30 transition font-medium flex items-center"
                                            title="Salir del Equipo">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="mb-6">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Proyecto
                                    </h4>
                                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                                        {{ $proyecto->nombre ?? 'Sin definir' }}</h2>
                                    <p
                                        class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-100 dark:border-gray-700">
                                        {{ $proyecto->descripcion ?? 'Agrega una descripción para los jueces.' }}
                                    </p>
                                    @if ($proyecto && $proyecto->repositorio_url)
                                        <div class="mt-4">
                                            <a href="{{ $proyecto->repositorio_url }}" target="_blank"
                                                class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                                </svg>
                                                Ver Repositorio
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                                        Integrantes</h4>
                                    <div class="flex flex-wrap gap-4">
                                        @foreach ($equipo->participantes as $miembro)
                                            <div
                                                class="flex items-center gap-3 p-2 pr-4 bg-white dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 rounded-full shadow-sm">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-xs font-bold text-white uppercase">
                                                    {{ substr($miembro->user->name, 0, 1) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-xs font-bold text-gray-700 dark:text-gray-200">{{ explode(' ', $miembro->user->name)[0] }}</span>
                                                    <span
                                                        class="text-[10px] text-gray-500 dark:text-gray-400">{{ $miembro->pivot->perfil->nombre ?? 'Miembro' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. RETROALIMENTACIÓN --}}
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                Retroalimentación de Jueces
                            </h3>
                            @if ($proyecto && $proyecto->comentarios->isNotEmpty())
                                <div class="space-y-4">
                                    @foreach ($proyecto->comentarios as $comentario)
                                        <div
                                            class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg border-l-2 border-indigo-500">
                                            <div class="flex justify-between items-center mb-1">
                                                <span
                                                    class="text-xs font-bold text-indigo-600 dark:text-indigo-400">Juez
                                                    #{{ $loop->iteration }}</span>
                                                <span
                                                    class="text-[10px] text-gray-400">{{ $comentario->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 italic">
                                                "{{ $comentario->comentario }}"</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div
                                    class="text-center py-6 border border-dashed border-gray-200 dark:border-gray-700 rounded-lg">
                                    <p class="text-sm text-gray-400 italic">Aún no hay comentarios disponibles.</p>
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{--               COLUMNA DERECHA (1/3)               --}}
                    {{-- ================================================= --}}
                    <div class="lg:col-span-1 space-y-6" x-data="{ showMetricsModal: false }">

                        {{-- 1. CALIFICACIÓN GLOBAL --}}
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Calificación Global</p>
                                <h4 class="text-xl font-bold text-gray-800 dark:text-white mt-1">
                                    {{ number_format($puntajeTotal * 10, 1) }} <span
                                        class="text-xs text-gray-400 font-normal">/ 100</span>
                                </h4>
                            </div>
                            <div
                                class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        {{-- 2. CONSTANCIAS --}}
                        @if ($equipo && $proyecto && $puntajeTotal > 0)
                            <div
                                class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                                <div
                                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl">
                                </div>
                                <h3 class="font-bold text-lg mb-1 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                        </path>
                                    </svg>
                                    Certificados
                                </h3>
                                <p class="text-indigo-100 text-xs mb-4">Evaluación completada. Descarga tus documentos.
                                </p>
                                <div class="space-y-2 relative z-10">
                                    <a href="{{ route('participante.constancia.imprimir', 'individual') }}"
                                        target="_blank"
                                        class="block w-full text-center py-2 px-4 bg-white/20 hover:bg-white/30 border border-white/30 rounded-lg text-xs font-bold uppercase transition backdrop-blur-sm">Individual</a>
                                    <a href="{{ route('participante.constancia.imprimir', 'equipo') }}"
                                        target="_blank"
                                        class="block w-full text-center py-2 px-4 bg-white text-indigo-600 hover:bg-indigo-50 rounded-lg text-xs font-bold uppercase transition shadow-sm">Equipo</a>
                                </div>
                            </div>
                        @endif

                        {{-- 3. GRÁFICO PEQUEÑO (TRIGGER DEL MODAL) --}}
                        <div @click="showMetricsModal = true"
                            class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition-all duration-300 relative">
                            <div class="flex justify-between items-center mb-4">
                                <h3
                                    class="text-sm font-bold text-gray-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    Calificacion por criterio</h3>
                                <span
                                    class="text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-500 px-2 py-1 rounded group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">Ver
                                    Detalle</span>
                            </div>

                            <div class="relative h-40 opacity-80 group-hover:opacity-100 transition-opacity">
                                <canvas id="miniChart"></canvas>
                                @if ($puntajeTotal == 0)
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-gray-800/50 backdrop-blur-[1px]">
                                        <span
                                            class="text-xs text-gray-500 bg-white dark:bg-gray-900 px-3 py-1 rounded-full shadow-sm border border-gray-100 dark:border-gray-700">Pendiente</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- 4. CALENDARIO --}}
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4">Próximos Eventos</h3>
                            @if ($eventos_proximos->isEmpty())
                                <div class="text-center py-4">
                                    <p class="text-xs text-gray-400">No hay eventos.</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach ($eventos_proximos as $evento)
                                        <div
                                            class="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition">
                                            <div
                                                class="flex flex-col items-center justify-center w-10 h-10 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-100 dark:border-indigo-800 flex-shrink-0">
                                                <span
                                                    class="text-[9px] text-indigo-600 dark:text-indigo-400 font-bold uppercase leading-none mb-0.5">{{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->shortMonthName }}</span>
                                                <span
                                                    class="text-sm text-indigo-700 dark:text-indigo-300 font-bold leading-none">{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d') }}</span>
                                            </div>
                                            <div class="overflow-hidden min-w-0">
                                                <p class="text-sm font-bold text-gray-700 dark:text-gray-200 truncate">
                                                    {{ $evento->nombre }}</p>
                                                <p class="text-[10px] text-gray-400">Cierre:
                                                    {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- ======================= --}}
                        {{-- MODAL DE MÉTRICAS DETALLE --}}
                        {{-- ======================= --}}
                        <div x-show="showMetricsModal" style="display: none;"
                            class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                            aria-modal="true">

                            <div x-show="showMetricsModal" x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm">
                            </div>

                            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                                <div x-show="showMetricsModal" @click.away="showMetricsModal = false"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    class="relative bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-3xl w-full border border-gray-200 dark:border-gray-700">

                                    <div
                                        class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                        <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white"
                                            id="modal-title">
                                            Desglose de Calificaciones
                                        </h3>
                                        <button @click="showMetricsModal = false"
                                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                            <span class="sr-only">Cerrar</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="px-6 py-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                            <div
                                                class="relative h-64 md:h-auto flex items-center justify-center bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-dashed border-gray-200 dark:border-gray-700">
                                                <canvas id="detailChart"></canvas>
                                            </div>

                                            <div>
                                                <h4
                                                    class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">
                                                    Detalle por Criterio
                                                </h4>
                                                <div class="space-y-4 overflow-y-auto max-h-80 pr-2">
                                                    @if (isset($chartLabels) && isset($chartData))
                                                        @foreach ($chartLabels as $index => $label)
                                                            <div class="group">
                                                                <div class="flex justify-between items-end mb-1">
                                                                    <span
                                                                        class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $label }}</span>
                                                                    <span
                                                                        class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                                                        {{ $chartData[$index] ?? 0 }}<span
                                                                            class="text-xs text-gray-400">/100</span>
                                                                    </span>
                                                                </div>
                                                                <div
                                                                    class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500"
                                                                        style="width: {{ $chartData[$index] ?? 0 }}%">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p class="text-gray-400 text-sm">Sin datos para mostrar.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3 sm:flex sm:flex-row-reverse border-t border-gray-100 dark:border-gray-700">
                                        <button type="button" @click="showMetricsModal = false"
                                            class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                            Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
    {{-- ================================================= --}}
    {{--   VISTA PARA PARTICIPANTE SIN EQUIPO (Dashboard)    --}}
    {{-- ================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        {{-- Card: Crear un Nuevo Equipo --}}
        <a href="{{ route('participante.equipos.create') }}"
            class="group relative bg-white dark:bg-gray-800 p-8 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg hover:border-indigo-500 dark:hover:border-indigo-500 transition-all duration-300">
            <div
                class="absolute top-4 right-4 w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-full flex items-center justify-center group-hover:bg-indigo-600 transition-colors duration-300">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 group-hover:text-white transition-colors"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <h3
                class="text-xl font-bold text-gray-900 dark:text-white mb-2 pr-16 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                Crear un Nuevo Equipo</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-16">Registra tu idea de
                proyecto,
                conviértete en líder y recluta a tus compañeros.</p>
            <span
                class="inline-flex items-center text-sm font-bold text-indigo-600 dark:text-indigo-400 group-hover:translate-x-1 transition-transform">
                Comenzar Registro <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </span>
        </a>

        {{-- Card: Unirme a un Equipo --}}
        <a href="{{ route('participante.equipos.join') }}"
            class="group relative bg-white dark:bg-gray-800 p-8 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg hover:border-purple-500 dark:hover:border-purple-500 transition-all duration-300">
            <div
                class="absolute top-4 right-4 w-12 h-12 bg-purple-50 dark:bg-purple-900/20 rounded-full flex items-center justify-center group-hover:bg-purple-600 transition-colors duration-300">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:text-white transition-colors"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
            <h3
                class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                Unirme a un Equipo</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-16">Explora los equipos
                existentes que buscan talento y postúlate con tu perfil.</p>
            <span
                class="inline-flex items-center text-sm font-bold text-purple-600 dark:text-purple-400 group-hover:translate-x-1 transition-transform">
                Ver Vacantes <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </span>
        </a>

        {{-- Card: Próximos Eventos --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4">Próximos Eventos</h3>
            @if (isset($eventos_proximos) && $eventos_proximos->isNotEmpty())
                <div class="space-y-3">
                    @foreach ($eventos_proximos as $evento)
                        <div
                            class="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition">
                            <div
                                class="flex flex-col items-center justify-center w-10 h-10 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-100 dark:border-indigo-800 flex-shrink-0">
                                <span
                                    class="text-[9px] text-indigo-600 dark:text-indigo-400 font-bold uppercase leading-none mb-0.5">{{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->shortMonthName }}</span>
                                <span
                                    class="text-sm text-indigo-700 dark:text-indigo-300 font-bold leading-none">{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d') }}</span>
                            </div>
                            <div class="overflow-hidden min-w-0">
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-200 truncate"
                                    title="{{ $evento->nombre }}">
                                    {{ $evento->nombre }}</p>
                                <p class="text-[10px] text-gray-400">Cierre:
                                    {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-xs text-gray-400">No hay eventos próximos.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ================================================= --}}
    {{--   CALENDARIO COMPLETO DE EVENTOS (Debajo de todo)   --}}
    {{-- ================================================= --}}
    {{-- CONTENEDOR CALENDARIO (Estilo Dark Glass) --}}
    <div class="bg-white dark:bg-[#1a222c] border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xl sm:rounded-2xl p-6 relative">

        <!-- Calendar Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            
            {{-- Título (Ej: "2025" o "Enero 2025") --}}
            <h3 id="currentLabel" class="text-3xl font-black text-gray-800 dark:text-white capitalize tracking-tight">
                <!-- JS -->
            </h3>

            {{-- Controles Agrupados --}}
            <div class="flex items-center gap-2 bg-gray-100 dark:bg-[#24303f] p-1 rounded-xl border border-gray-200 dark:border-gray-700">
                
                {{-- Botón Hoy --}}
                <button id="todayBtn" class="px-4 py-1.5 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 rounded-lg shadow-sm transition-all">
                    Hoy
                </button>

                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                {{-- Navegación --}}
                <button id="prevBtn" class="p-1.5 rounded-lg hover:bg-white dark:hover:bg-gray-600 text-gray-500 dark:text-gray-400 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button id="nextBtn" class="p-1.5 rounded-lg hover:bg-white dark:hover:bg-gray-600 text-gray-500 dark:text-gray-400 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                {{-- Selector de Vista --}}
                <select id="viewSelector" class="bg-transparent border-none text-sm font-bold text-gray-700 dark:text-white focus:ring-0 cursor-pointer py-1.5 pl-2 pr-8">
                    <option value="month" class="text-black">Vista Mensual</option>
                    <option value="year" class="text-black">Vista del año</option>
                </select>
            </div>
        </div>

        <!-- 1. VISTA MENSUAL (Month View) -->
        <div id="monthView" class="transition-opacity duration-300">
            {{-- Encabezados de días --}}
            <div class="grid grid-cols-7 mb-2">
                @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $day)
                    <div class="text-center font-bold text-gray-400 dark:text-gray-500 uppercase text-xs tracking-widest py-2">{{ $day }}</div>
                @endforeach
            </div>
            
            {{-- Rejilla Mensual --}}
            <div id="monthGrid" class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <!-- JS inyectará los días aquí -->
            </div>
        </div>

        <!-- 2. VISTA ANUAL (Year View - Estilo Pro) -->
        <div id="yearView" class="hidden transition-opacity duration-300">
            <div id="yearGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <!-- JS inyectará los 12 meses aquí -->
            </div>
        </div>

    </div>

    <style>
        /* Scrollbar oculta para limpieza visual */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        /* Truco para bordes compartidos en grid */
        #calendarGrid {
            border-collapse: collapse;
        }
    </style>

    <script>
        const events = @json($eventos_proximos ?? []);
        
        const urlParams = new URLSearchParams(window.location.search);
        const dateParam = urlParams.get('date');
        let currentDate = dateParam ? new Date(dateParam) : new Date();
        let viewMode = 'year'; // Empezamos en Year View
        let tooltipTimeout;

        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        const dayInitials = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];

        function render() {
            const currentLabel = document.getElementById('currentLabel');
            const viewSelector = document.getElementById('viewSelector');
            const monthView = document.getElementById('monthView');
            const yearView = document.getElementById('yearView');

            // Actualizar selector visual
            viewSelector.value = viewMode;

            if (viewMode === 'month') {
                monthView.classList.remove('hidden');
                yearView.classList.add('hidden');
                currentLabel.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
                renderMonthGrid();
            } else {
                monthView.classList.add('hidden');
                yearView.classList.remove('hidden');
                currentLabel.textContent = `${currentDate.getFullYear()}`;
                renderYearGrid();
            }
        }

        // --- RENDERIZADO VISTA MENSUAL (Detallada) ---
        function renderMonthGrid() {
            const grid = document.getElementById('monthGrid');
            grid.innerHTML = '';

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            // Rellenar días previos
            for (let i = firstDay - 1; i >= 0; i--) {
                const cell = createMonthCell(daysInPrevMonth - i, true);
                grid.appendChild(cell);
            }

            // Días actuales
            for (let day = 1; day <= daysInMonth; day++) {
                const cell = createMonthCell(day, false);
                
                // Eventos
                const dayEvents = events.filter(e => {
                    const eDate = new Date(e.fecha_inicio);
                    return eDate.getDate() === day && eDate.getMonth() === month && eDate.getFullYear() === year;
                });

                // Renderizar pastillas de eventos
                dayEvents.forEach(event => {
                    const eventEl = document.createElement('div');
                    eventEl.className = 'mt-1 px-2 py-0.5 text-[10px] font-medium rounded cursor-pointer truncate transition-all hover:opacity-80';
                    
                    // Colores de estado
                    const now = new Date();
                    const start = new Date(event.fecha_inicio);
                    if (now < start) {
                        eventEl.classList.add('bg-blue-100', 'text-blue-700', 'dark:bg-blue-500/20', 'dark:text-blue-300');
                    } else if (now > new Date(event.fecha_fin)) {
                        eventEl.classList.add('bg-gray-100', 'text-gray-600', 'dark:bg-gray-700', 'dark:text-gray-400');
                    } else {
                        eventEl.classList.add('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-500/30', 'dark:text-indigo-300', 'border-l-2', 'border-indigo-500');
                    }
                    
                    eventEl.textContent = event.nombre;
                    eventEl.onclick = (e) => {
                        e.stopPropagation();
                        // Participante no tiene permiso para ver detalles
                    };
                    eventEl.onmouseenter = (e) => { clearTimeout(tooltipTimeout); showTooltip(e, event); };
                    eventEl.onmouseleave = () => { tooltipTimeout = setTimeout(hideTooltip, 300); };

                    cell.appendChild(eventEl);
                });

                grid.appendChild(cell);
            }

            // Rellenar días siguientes para cuadrar
            const totalCells = grid.children.length;
            const remainingCells = 42 - totalCells; 
            for (let i = 1; i <= remainingCells; i++) {
                const cell = createMonthCell(i, true);
                grid.appendChild(cell);
            }
        }

        function createMonthCell(dayNumber, isGray) {
            const cell = document.createElement('div');
            cell.className = `min-h-[8rem] p-2 bg-white dark:bg-[#1a222c] transition-colors ${isGray ? 'bg-gray-50/50 dark:bg-[#151b23] text-gray-400 dark:text-gray-600' : 'hover:bg-gray-50 dark:hover:bg-[#24303f]'}`;
            
            const dateNum = document.createElement('div');
            dateNum.textContent = dayNumber;
            dateNum.className = `text-sm font-medium mb-1 ${isGray ? '' : 'text-gray-700 dark:text-gray-300'}`;

            // Highlight Hoy
            const today = new Date();
            if (!isGray && dayNumber === today.getDate() && currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear()) {
                dateNum.className = 'w-7 h-7 flex items-center justify-center bg-indigo-600 text-white rounded-full text-sm font-bold mb-1 shadow-lg shadow-indigo-500/50';
            }

            cell.appendChild(dateNum);
            return cell;
        }


        // --- RENDERIZADO VISTA ANUAL (Estilo Imagen) ---
        function renderYearGrid() {
            const grid = document.getElementById('yearGrid');
            grid.innerHTML = '';

            monthNames.forEach((name, monthIndex) => {
                // Contenedor del Mes
                const monthCard = document.createElement('div');
                // Sin bordes, fondo transparente o muy sutil para que se vea limpio
                monthCard.className = 'p-2'; 

                // Título del Mes
                const title = document.createElement('h4');
                title.textContent = name;
                title.className = 'text-center font-bold text-gray-800 dark:text-gray-200 mb-4 cursor-pointer hover:text-indigo-500 transition-colors';
                title.onclick = () => {
                    currentDate.setMonth(monthIndex);
                    viewMode = 'month';
                    render();
                };
                monthCard.appendChild(title);

                // Cabecera de días (D L M M J V S)
                const daysHeader = document.createElement('div');
                daysHeader.className = 'grid grid-cols-7 mb-2';
                dayInitials.forEach(initial => {
                    const d = document.createElement('div');
                    d.textContent = initial;
                    d.className = 'text-center text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase';
                    daysHeader.appendChild(d);
                });
                monthCard.appendChild(daysHeader);

                // Rejilla de días del mes
                const miniGrid = document.createElement('div');
                miniGrid.className = 'grid grid-cols-7 gap-y-1'; // Gap vertical pequeño, horizontal 0

                const year = currentDate.getFullYear();
                const firstDay = new Date(year, monthIndex, 1).getDay();
                const daysInMonth = new Date(year, monthIndex + 1, 0).getDate();

                // Espacios vacíos iniciales
                for (let i = 0; i < firstDay; i++) {
                    miniGrid.appendChild(document.createElement('div'));
                }

                // Días
                for (let day = 1; day <= daysInMonth; day++) {
                    const dayCell = document.createElement('div');
                    dayCell.textContent = day;
                    
                    // Estilo base del número
                    let cellClass = 'h-8 w-8 mx-auto flex items-center justify-center text-xs rounded-full cursor-pointer transition-all duration-200 ';
                    
                    // Verificar eventos
                    const hasEvents = events.some(e => {
                        const eDate = new Date(e.fecha_inicio);
                        return eDate.getDate() === day && eDate.getMonth() === monthIndex && eDate.getFullYear() === year;
                    });

                    const isToday = (day === new Date().getDate() && monthIndex === new Date().getMonth() && year === new Date().getFullYear());

                    if (isToday) {
                        // Estilo HOY (Círculo Azul Brillante como en la imagen)
                        cellClass += 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-500/50';
                    } else if (hasEvents) {
                        // Días con eventos (Sutilmente marcados)
                        cellClass += 'text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-800';
                    } else {
                        // Días normales
                        cellClass += 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white';
                    }

                    dayCell.className = cellClass;
                    
                    // Click para ir a ver ese día (cambia a mes)
                    dayCell.onclick = (e) => {
                        e.stopPropagation();
                        currentDate.setMonth(monthIndex);
                        currentDate.setDate(day); // Opcional: podrías resaltar el día al cambiar
                        viewMode = 'month';
                        render();
                    };

                    // Agregar tooltips si el día tiene eventos
                    if (hasEvents) {
                        const dayEvents = events.filter(e => {
                            const eDate = new Date(e.fecha_inicio);
                            return eDate.getDate() === day && eDate.getMonth() === monthIndex && eDate.getFullYear() === year;
                        });
                        
                        // Mostrar tooltip con el primer evento del día
                        dayCell.onmouseenter = (e) => {
                            clearTimeout(tooltipTimeout);
                            showTooltip(e, dayEvents[0]);
                        };
                        dayCell.onmouseleave = () => {
                            tooltipTimeout = setTimeout(hideTooltip, 300);
                        };
                    }

                    miniGrid.appendChild(dayCell);
                }

                monthCard.appendChild(miniGrid);
                grid.appendChild(monthCard);
            });
        }

        // --- CONTROLES ---
        document.getElementById('prevBtn').addEventListener('click', () => {
            if (viewMode === 'month') currentDate.setMonth(currentDate.getMonth() - 1);
            else currentDate.setFullYear(currentDate.getFullYear() - 1);
            render();
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            if (viewMode === 'month') currentDate.setMonth(currentDate.getMonth() + 1);
            else currentDate.setFullYear(currentDate.getFullYear() + 1);
            render();
        });

        document.getElementById('todayBtn').addEventListener('click', () => {
            currentDate = new Date();
            render();
        });

        document.getElementById('viewSelector').addEventListener('change', (e) => {
            viewMode = e.target.value;
            render();
        });

        // --- TOOLTIP (Mismo de antes) ---
        const tooltip = document.createElement('div');
        tooltip.className = 'fixed hidden z-50 w-64 bg-white dark:bg-[#24303f] rounded-lg shadow-2xl p-4 border border-gray-200 dark:border-gray-600 text-sm pointer-events-none';
        document.body.appendChild(tooltip);

        function showTooltip(e, event) {
            const rect = e.target.getBoundingClientRect();
            let left = rect.left;
            if (left + 256 > window.innerWidth) left = window.innerWidth - 270;
            
            tooltip.style.left = `${left}px`;
            tooltip.style.top = `${rect.bottom + 10}px`;
            tooltip.classList.remove('hidden');
            
            const start = new Date(event.fecha_inicio).toLocaleDateString();
            const end = new Date(event.fecha_fin).toLocaleDateString();

            tooltip.innerHTML = `
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">${event.nombre}</h4>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-xs mb-3 line-clamp-2 leading-relaxed">${event.descripcion || 'Sin descripción'}</p>
                <div class="text-[10px] uppercase tracking-wider font-semibold text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-gray-700 pt-2">
                    ${start} - ${end}
                </div>
            `;
        }
        function hideTooltip() { tooltip.classList.add('hidden'); }

        // Iniciar
        render();
    </script>
@endif

    {{-- SCRIPT GRÁFICO (BARRAS HORIZONTALES) --}}
    @if ($equipo && $proyecto)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Datos compartidos
                const labels = @json($chartLabels ?? []);
                const data = @json($chartData ?? []);
                const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                // Colores
                const textColor = isDark ? '#cbd5e1' : '#64748b';
                const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
                const barColor = 'rgba(99, 102, 241, 0.8)'; // Indigo

                // 1. MINI CHART (Vista Previa - Barras horizontales)
                const ctxMini = document.getElementById('miniChart').getContext('2d');
                new Chart(ctxMini, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: barColor,
                            borderRadius: 3,
                            barThickness: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        scales: {
                            // CORRECCIÓN: Eje X máximo 100
                            x: {
                                display: false,
                                max: 100,
                                min: 0
                            },
                            y: {
                                display: false
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        },
                        interaction: {
                            mode: 'none'
                        }
                    }
                });

                // 2. DETAIL CHART (Dentro del Modal - Radar)
                const ctxDetail = document.getElementById('detailChart').getContext('2d');
                new Chart(ctxDetail, {
                    type: 'radar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Puntaje',
                            data: data,
                            backgroundColor: 'rgba(99, 102, 241, 0.2)',
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            pointBackgroundColor: '#6366f1',
                            pointBorderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                angleLines: {
                                    color: gridColor
                                },
                                grid: {
                                    color: gridColor
                                },
                                pointLabels: {
                                    color: textColor,
                                    font: {
                                        size: 11
                                    }
                                },
                                ticks: {
                                    display: false,
                                    stepSize: 20
                                }, // Pasos de 20 en 20
                                // CORRECCIÓN: Escala Radial de 0 a 100
                                min: 0,
                                max: 100,
                                suggestedMax: 100
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
        </script>
    @endif
</x-app-layout>