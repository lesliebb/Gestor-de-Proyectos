<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Evaluación de Proyecto
            </h2>
            <a href="{{ route('juez.evento.show', $proyecto->evento_id) }}"
                class="text-sm font-medium text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Evento
            </a>
        </div>
    </x-slot>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Inicializamos Alpine con los datos del servidor --}}
    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="evaluador({
        criterios: {{ $proyecto->evento->criterios->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre, 'peso' => $c->ponderacion]) }},
        previas: {{ json_encode($calificacionesPrevias) }}
    })">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- ================================================= --}}
                {{-- COLUMNA IZQUIERDA: RÚBRICA (2/3)                  --}}
                {{-- ================================================= --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- 1. INFO PROYECTO --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>

                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $proyecto->nombre }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                                    {{ $proyecto->descripcion }}</p>
                            </div>
                            @if ($proyecto->repositorio_url)
                                <a href="{{ $proyecto->repositorio_url }}" target="_blank"
                                    class="flex-shrink-0 ml-4 inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                    </svg>
                                    Repositorio
                                </a>
                            @endif
                        </div>
                    </div>

                    <form id="evalForm" method="POST" action="{{ route('juez.evaluaciones.store', $proyecto) }}">
                        @csrf

                        {{-- 2. LISTA DE CRITERIOS --}}
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Rúbrica de Evaluación</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Evalúa cada criterio del 0 al
                                    100.</p>
                            </div>

                            <div class="p-6 space-y-8">
                                <template x-for="criterio in criterios" :key="criterio.id">
                                    <div class="group">
                                        <div class="flex justify-between items-end mb-4">
                                            <div class="flex items-center gap-2">
                                                <label class="text-base font-bold text-gray-800 dark:text-gray-200"
                                                    x-text="criterio.nombre"></label>
                                                <span
                                                    class="text-[10px] font-bold bg-gray-100 text-gray-600 px-2 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                                    Peso: <span x-text="criterio.peso"></span>%
                                                </span>
                                            </div>
                                            <div class="text-right flex items-center justify-end gap-1">
                                                <input type="number" 
                                                    min="0" 
                                                    max="100" 
                                                    x-model.number="scores[criterio.id]"
                                                    @input="updateChart()"
                                                    @blur="if(scores[criterio.id] < 0) scores[criterio.id] = 0; if(scores[criterio.id] > 100) scores[criterio.id] = 100; updateChart()"
                                                    class="w-16 text-2xl font-black text-right bg-transparent border-0 border-b-2 border-transparent hover:border-gray-300 focus:border-indigo-500 focus:ring-0 transition-all duration-75 dark:text-white dark:hover:border-gray-600 dark:focus:border-indigo-400 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                    :style="'color: hsl(' + ((scores[criterio.id] || 0) * 1.2) + ', 80%, 45%)'"
                                                />
                                                <span class="text-sm text-gray-400 font-medium">/100</span>
                                            </div>
                                        </div>

                                        <div class="relative w-full h-4">
                                            <div
                                                class="absolute w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full top-1">
                                            </div>

                                            <div class="absolute h-2 rounded-full top-1 transition-all duration-75 ease-out"
                                                :style="'width: ' + (scores[criterio.id] || 0) + '%; background-color: hsl(' + (
                                                    (scores[criterio.id] || 0) * 1.2) +
                                                ', 85%, 50%); box-shadow: 0 0 10px hsl(' + ((scores[criterio.id] || 0) *
                                                    1.2) + ', 85%, 50%, 0.5)'">

                                                <div
                                                    class="absolute right-0 top-0 h-2 w-2 rounded-full bg-white opacity-60">
                                                </div>
                                            </div>

                                            <input type="range" min="0" max="100"
                                                x-model.number="scores[criterio.id]" @input="updateChart()"
                                                :name="'puntuaciones[' + criterio.id + ']'"
                                                class="absolute w-full h-4 opacity-0 cursor-pointer z-10 top-0">
                                        </div>

                                        <div
                                            class="flex justify-between text-[10px] uppercase font-bold text-gray-400 mt-2 tracking-wider">
                                            <span>Deficiente</span>
                                            <span>Regular</span>
                                            <span>Excelente</span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- 3. FEEDBACK CUALITATIVO --}}
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 mt-6 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Feedback Cualitativo</h4>
                            </div>
                            <div class="p-6">
                                <label for="comentario"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Comentarios y
                                    Recomendaciones</label>
                                <textarea id="comentario" name="comentario" rows="4"
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all p-4 text-sm leading-relaxed"
                                    placeholder="Escribe aquí tus observaciones sobre fortalezas y áreas de mejora...">{{ old('comentario', $comentarioTexto) }}</textarea>
                                <p class="text-xs text-gray-500 mt-2 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Este comentario será visible para el equipo.
                                </p>
                            </div>
                        </div>

                    </form>
                </div>

                {{-- ================================================= --}}
                {{-- COLUMNA DERECHA: RESUMEN STICKY (1/3)             --}}
                {{-- ================================================= --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">

                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 text-center">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Calificación
                                Final</h3>

                            <div class="relative h-48 w-48 mx-auto mb-6">
                                <canvas id="realTimeChart"></canvas>
                                <div
                                    class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                    <span class="text-4xl font-black text-gray-900 dark:text-white"
                                        x-text="calculateTotal()"></span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Puntos</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                                <button type="submit" form="evalForm"
                                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Guardar Evaluación
                                </button>
                                <a href="{{ route('juez.evento.show', $proyecto->evento_id) }}"
                                    class="block mt-4 text-xs font-bold text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white uppercase tracking-wide transition-colors">
                                    Cancelar
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script de Lógica Alpine + Chart.js --}}
    <script>
        function evaluador(data) {
            return {
                criterios: data.criterios,
                scores: {},
                chart: null,

                init() {
                    // 1. Inicializar puntuaciones
                    this.criterios.forEach(c => {
                        this.scores[c.id] = data.previas[c.id] ? parseInt(data.previas[c.id]) : 0;
                    });

                    // 2. Inicializar Gráfico
                    this.initChart();
                },

                calculateTotal() {
                    let total = 0;
                    this.criterios.forEach(c => {
                        let puntos = this.scores[c.id] || 0;
                        total += (puntos * c.peso) / 100;
                    });
                    return total.toFixed(1);
                },

                initChart() {
                    const ctx = document.getElementById('realTimeChart').getContext('2d');

                    // Configuración Chart.js
                    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    this.chart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: this.criterios.map(c => c.nombre),
                            datasets: [{
                                data: this.getChartData(),
                                backgroundColor: [
                                    '#6366f1', '#8b5cf6', '#ec4899', '#f43f5e',
                                    '#10b981' // Paleta TailAdmin
                                ],
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%', // Agujero más grande para el texto
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: false
                                } // Desactivamos tooltip para limpieza visual
                            }
                        }
                    });
                },

                getChartData() {
                    // Mapeamos los scores ponderados para que el gráfico refleje el peso real
                    return this.criterios.map(c => {
                        let puntos = this.scores[c.id] || 0;
                        return (puntos * c.peso) / 100; // Valor proporcional
                    });
                },

                updateChart() {
                    if (this.chart) {
                        this.chart.data.datasets[0].data = this.getChartData();
                        this.chart.update();
                    }
                }
            }
        }
    </script>
</x-app-layout>
