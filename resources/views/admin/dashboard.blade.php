<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    {{-- CDN de Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- SECCIÓN 1: Tarjetas de Resumen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs uppercase font-bold mb-1">Usuarios</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                        {{ $total_jueces + $total_participantes }}
                    </div>
                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        {{ $total_jueces }} Jueces, {{ $total_participantes }} Alumnos
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs uppercase font-bold mb-1">Equipos</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $total_equipos }}</div>
                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">Registrados</div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs uppercase font-bold mb-1">Proyectos</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $total_proyectos }}</div>
                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $proyectosEvaluados }} Evaluados</div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs uppercase font-bold mb-1">Eventos Activos</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $eventos_activos->count() }}
                    </div>
                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">En curso</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- SECCIÓN 2: Gráficos (Ocupa 2 columnas) --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Métricas del Sistema</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Gráfico 1: Carreras --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 text-center">
                                Participación por Carrera</h4>
                            <div class="relative h-80">
                                <canvas id="chartCarreras"></canvas>
                            </div>
                        </div>

                        {{-- Gráfico 2: Progreso Evaluación --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 text-center">Progreso
                                de Evaluación</h4>
                            <div class="relative h-80">
                                <canvas id="chartEvaluacion"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 3: Calendario Simple (Ocupa 1 columna) --}}
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Eventos Próximos</h3>

                    @if($eventos_activos->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No hay eventos programados pronto.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($eventos_activos as $evento)
                                <div onclick="window.location.href='{{ route('admin.eventos.index', ['date' => $evento->fecha_inicio]) }}'"
                                    class="flex items-start space-x-3 p-3 bg-indigo-50 dark:bg-gray-900 rounded-lg border border-indigo-100 dark:border-gray-700 transform transition hover:scale-110 duration-300 cursor-pointer">
                                    <div
                                        class="bg-white dark:bg-gray-800 p-2 rounded text-center border border-indigo-200 dark:border-gray-600 min-w-[50px]">
                                        <span class="block text-xs text-indigo-500 font-bold uppercase">
                                            {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->shortMonthName }}
                                        </span>
                                        <span class="block text-xl font-bold text-gray-800 dark:text-gray-200">
                                            {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d') }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-indigo-900 dark:text-indigo-300">{{ $evento->nombre }}
                                        </h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                            {{ $evento->descripcion }}
                                        </p>
                                        <div class="mt-2 text-xs text-indigo-500">
                                            Termina: {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-6 pt-4 border-t dark:border-gray-700 text-center">
                        <a href="{{ route('admin.eventos.index') }}"
                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Ver Calendario Completo
                            →</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Scripts para los Gráficos --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Datos desde el controlador
            const dataCarreras = @json($participantesPorCarrera);
            const evaluados = {{ $proyectosEvaluados }};
            const pendientes = {{ $proyectosPendientes }};

            // Función para obtener color de texto según el tema
            const getThemeTextColor = () => {
                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    return '#9ca3af'; // gray-400
                }
                return '#6b7280'; // gray-500
            };

            // 1. Configuración Gráfico Carreras (Pastel)
            const ctxCarreras = document.getElementById('chartCarreras').getContext('2d');
            new Chart(ctxCarreras, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(dataCarreras),
                    datasets: [{
                        data: Object.values(dataCarreras),
                        backgroundColor: ['#6366f1', '#ec4899', '#10b981', '#f59e0b', '#3b82f6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 10 },
                                color: getThemeTextColor()
                            }
                        }
                    }
                }
            });

            // 2. Configuración Gráfico Evaluación (Barras)
            const ctxEval = document.getElementById('chartEvaluacion').getContext('2d');
            new Chart(ctxEval, {
                type: 'bar',
                data: {
                    labels: ['Evaluados', 'Pendientes'],
                    datasets: [{
                        label: 'Proyectos',
                        data: [evaluados, pendientes],
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: getThemeTextColor()
                            }
                        },
                        x: {
                            ticks: {
                                color: getThemeTextColor()
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>