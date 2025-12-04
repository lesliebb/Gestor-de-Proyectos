<x-app-layout>
    {{-- Header Actions --}}
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            {{ __('Dashboard') }}
        </h2>
        <div class="flex items-center gap-3">
            {{-- Generate PDF Button --}}
            <a href="{{ route('admin.dashboard.report') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Generar Reporte PDF
            </a>

            {{-- Settings Dropdown --}}
            <div class="relative z-30" x-data="{ open: false }">
                <button @click="open = !open"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Personalizar
                </button>

                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-80 rounded-xl bg-white shadow-xl ring-1 ring-black ring-opacity-5 z-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-0 overflow-hidden"
                    style="display: none;">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white">Configuración del Tablero</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Arrastra los widgets para reordenarlos.
                            Usa este menú para ocultar o cambiar tipos.</p>
                    </div>

                    <form id="preferencesForm" class="max-h-[400px] overflow-y-auto p-4 space-y-4">
                        {{-- Section: Stats Cards --}}
                        <div>
                            <h4 class="text-xs font-bold uppercase text-gray-400 mb-2 tracking-wider">Tarjetas de
                                Resumen</h4>
                            <div class="space-y-2">
                                @foreach ($widgets as $widget)
                                    @if (str_contains($widget['key'], 'stats'))
                                        <label
                                            class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer border border-transparent hover:border-gray-200 dark:hover:border-gray-600 transition">
                                            <span
                                                class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                <input type="checkbox"
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 widget-visibility"
                                                    data-key="{{ $widget['key'] }}"
                                                    {{ $widget['is_visible'] ? 'checked' : '' }}>
                                                {{ match ($widget['key']) {
                                                    'stats_users' => 'Total Usuarios',
                                                    'stats_equipos' => 'Equipos Activos',
                                                    'stats_proyectos' => 'Proyectos',
                                                    'stats_eventos' => 'Eventos Activos',
                                                    default => $widget['key'],
                                                } }}
                                            </span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Section: Charts & Lists --}}
                        <div>
                            <h4 class="text-xs font-bold uppercase text-gray-400 mb-2 tracking-wider">Gráficos y Listas
                            </h4>
                            <div class="space-y-3">
                                @foreach ($widgets as $widget)
                                    @if (!str_contains($widget['key'], 'stats'))
                                        <div
                                            class="p-3 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                                            <div class="flex items-center justify-between mb-2">
                                                <label
                                                    class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                                    <input type="checkbox"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 widget-visibility"
                                                        data-key="{{ $widget['key'] }}"
                                                        {{ $widget['is_visible'] ? 'checked' : '' }}>
                                                    {{ match ($widget['key']) {
                                                        'chart_evaluacion' => 'Progreso de Evaluación',
                                                        'chart_carreras' => 'Participación por Carrera',
                                                        'chart_pendientes_anual' => 'Proyectos Pendientes (Anual)',
                                                        'chart_categorias' => 'Proyectos por Categoría',
                                                        'list_eventos' => 'Próximos Eventos',
                                                        default => $widget['key'],
                                                    } }}
                                                </label>
                                            </div>

                                            @if (str_contains($widget['key'], 'chart'))
                                                <div class="ml-6 space-y-2">
                                                    {{-- Chart Type Selector --}}
                                                    <div>
                                                        <label
                                                            class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Tipo
                                                            de Gráfico:</label>
                                                        <select
                                                            class="w-full text-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 widget-type"
                                                            data-key="{{ $widget['key'] }}">
                                                            <option value="bar"
                                                                {{ ($widget['settings']['type'] ?? '') == 'bar' ? 'selected' : '' }}>
                                                                Barras (Vertical)</option>
                                                            <option value="horizontalBar"
                                                                {{ ($widget['settings']['type'] ?? '') == 'horizontalBar' ? 'selected' : '' }}>
                                                                Barras (Horizontal)</option>
                                                            <option value="doughnut"
                                                                {{ ($widget['settings']['type'] ?? '') == 'doughnut' ? 'selected' : '' }}>
                                                                Dona</option>
                                                            <option value="line"
                                                                {{ ($widget['settings']['type'] ?? '') == 'line' ? 'selected' : '' }}>
                                                                Línea</option>
                                                            <option value="pie"
                                                                {{ ($widget['settings']['type'] ?? '') == 'pie' ? 'selected' : '' }}>
                                                                Pastel</option>
                                                        </select>
                                                    </div>

                                                    {{-- Event Selector (Only for Evaluation Chart) --}}
                                                    @if ($widget['key'] == 'chart_evaluacion')
                                                        <div>
                                                            <label
                                                                class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Filtrar
                                                                por Evento:</label>
                                                            <select
                                                                class="w-full text-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 widget-event"
                                                                data-key="{{ $widget['key'] }}">
                                                                <option value="">Todos los Eventos</option>
                                                                @foreach ($eventos_stats as $evt)
                                                                    <option value="{{ $evt['id'] }}"
                                                                        {{ ($widget['settings']['event_id'] ?? '') == $evt['id'] ? 'selected' : '' }}>
                                                                        {{ Str::limit($evt['nombre'], 25) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </form>

                    <div
                        class="p-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 text-right">
                        <button type="button" id="saveConfigBtn"
                            class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">
                            Aplicar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CDN de Chart.js y SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    {{-- Contenedor Principal Grid Sortable --}}
    <div id="dashboard-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pb-10">
        @foreach ($widgets as $widget)
            @if ($widget['is_visible'])
                <div class="widget-item relative group {{ str_contains($widget['key'], 'chart') || str_contains($widget['key'], 'list') ? 'col-span-1 md:col-span-2' : 'col-span-1' }}"
                    data-key="{{ $widget['key'] }}">

                    {{-- Drag Handle --}}
                    <div
                        class="drag-handle absolute top-3 right-3 p-1.5 rounded-md bg-white/80 dark:bg-gray-800/80 text-gray-400 hover:text-indigo-600 cursor-move transition z-10 shadow-sm backdrop-blur-sm border border-gray-200 dark:border-gray-600 hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </div>

                    {{-- WIDGET CONTENT SWITCHER --}}
                    @switch($widget['key'])
                        @case('stats_users')
                            <div
                                class="h-full rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 transition-transform hover:scale-[1.02]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Usuarios</p>
                                        <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">
                                            {{ $total_jueces + $total_participantes }}</h4>
                                    </div>
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center gap-2">
                                    <span
                                        class="flex items-center gap-1 text-sm font-medium text-green-600 dark:text-green-400">{{ $total_jueces }}
                                        Jueces</span>
                                    <span class="text-sm text-gray-400">|</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $total_participantes }}
                                        Alumnos</span>
                                </div>
                            </div>
                        @break

                        @case('stats_equipos')
                            <div
                                class="h-full rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 transition-transform hover:scale-[1.02]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Equipos Activos</p>
                                        <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">{{ $total_equipos }}
                                        </h4>
                                    </div>
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4"><span
                                        class="text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-2 py-1 rounded-md">Registrados</span>
                                </div>
                            </div>
                        @break

                        @case('stats_proyectos')
                            <div
                                class="h-full rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 transition-transform hover:scale-[1.02]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Proyectos</p>
                                        <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">
                                            {{ $total_proyectos }}</h4>
                                    </div>
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between text-sm">
                                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ $proyectosEvaluados }}
                                        Evaluados</span>
                                    <span class="text-gray-400">{{ $proyectosPendientes }} Pendientes</span>
                                </div>
                            </div>
                        @break

                        @case('stats_eventos')
                            <div
                                class="h-full rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 transition-transform hover:scale-[1.02]">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Eventos Activos</p>
                                        <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">
                                            {{ $eventos_activos->count() }}</h4>
                                    </div>
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-50 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4"><span
                                        class="text-sm font-medium text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-900/30 px-2 py-1 rounded-md">En
                                        curso</span></div>
                            </div>
                        @break

                        @case('chart_evaluacion')
                            <div
                                class="h-full rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Progreso de Evaluación</h3>
                                    @if (!empty($widget['settings']['event_id']))
                                        <span
                                            class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-2 py-1 rounded-full">
                                            {{ $eventos_stats->where('id', $widget['settings']['event_id'])->first()['nombre'] ?? 'Evento' }}
                                        </span>
                                    @endif
                                </div>
                                <div class="relative h-72 w-full">
                                    <canvas id="chartEvaluacion" data-type="{{ $widget['settings']['type'] ?? 'bar' }}"
                                        data-event="{{ $widget['settings']['event_id'] ?? '' }}"></canvas>
                                </div>
                            </div>
                        @break

                        @case('chart_carreras')
                            <div
                                class="h-full rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Participación por Carrera</h3>
                                <div class="relative h-72 w-full flex justify-center">
                                    <canvas id="chartCarreras"
                                        data-type="{{ $widget['settings']['type'] ?? 'doughnut' }}"></canvas>
                                </div>
                            </div>
                        @break

                        @case('chart_pendientes_anual')
                            <div
                                class="h-full rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Proyectos Pendientes (Anual)
                                </h3>
                                <div class="relative h-72 w-full flex justify-center">
                                    <canvas id="chartPendientesAnual"
                                        data-type="{{ $widget['settings']['type'] ?? 'line' }}"></canvas>
                                </div>
                            </div>
                        @break

                        @case('chart_categorias')
                            <div
                                class="h-full rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Proyectos por Categoría</h3>
                                <div class="relative h-72 w-full flex justify-center">
                                    <canvas id="chartCategorias"
                                        data-type="{{ $widget['settings']['type'] ?? 'bar' }}"></canvas>
                                </div>
                            </div>
                        @break

                        @case('list_eventos')
                            <div
                                class="h-full rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Próximos Eventos</h3>
                                    <a href="{{ route('admin.eventos.index') }}"
                                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Ver todo</a>
                                </div>
                                @if ($eventos_activos->isEmpty())
                                    <div class="text-center py-8">
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">No hay eventos programados.</p>
                                    </div>
                                @else
                                    <div class="space-y-4">
                                        @foreach ($eventos_activos as $evento)
                                            <div
                                                class="group flex items-start gap-4 p-3 rounded-xl border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer">
                                                <div
                                                    class="flex flex-col items-center justify-center h-14 w-14 rounded-lg bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-gray-600">
                                                    <span
                                                        class="text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->shortMonthName }}</span>
                                                    <span
                                                        class="text-xl font-bold">{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d') }}</span>
                                                </div>
                                                <div>
                                                    <h4
                                                        class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                                        {{ $evento->nombre }}</h4>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                                        {{ $evento->descripcion }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @break
                    @endswitch
                </div>
            @endif
        @endforeach
    </div>

    <style>
        .sortable-ghost {
            opacity: 0.4 !important;
            background-color: transparent !important;
            box-shadow: none !important;
        }

        .dark .sortable-ghost {
            border-color: #6366f1 !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. SORTABLE JS ---
            const grid = document.getElementById('dashboard-grid');
            if (grid) {
                new Sortable(grid, {
                    animation: 150,
                    // handle: '.drag-handle',  // Comentado para permitir arrastre desde todo el widget
                    ghostClass: 'sortable-ghost', // ← CAMBIAR ESTA LÍNEA (era 'bg-indigo-50')
                    onEnd: function() {
                        savePreferences();
                    }
                });
            }

            // --- 2. CHART CONFIGURATION & THEME HELPERS ---
            const isDark = () => document.documentElement.classList.contains('dark');

            const getThemeColors = () => ({
                text: isDark() ? '#9ca3af' : '#64748b', // gray-400 : gray-500
                grid: isDark() ? '#374151' : '#f1f5f9', // gray-700 : slate-100
                tooltipBg: isDark() ? '#1f2937' : '#ffffff',
                tooltipText: isDark() ? '#f3f4f6' : '#1f2937'
            });

            // Palette for charts
            const colors = {
                primary: '#4f46e5', // Indigo 600
                secondary: '#ec4899', // Pink 500
                success: '#10b981', // Emerald 500
                warning: '#f59e0b', // Amber 500
                info: '#3b82f6', // Blue 500
                purple: '#8b5cf6', // Violet 500
                gray: '#94a3b8', // Slate 400
                lightGray: '#e2e8f0', // Slate 200
                darkGray: '#334155' // Slate 700
            };

            const palette = [colors.primary, colors.secondary, colors.success, colors.warning, colors.info, colors
                .purple
            ];

            // Common Chart Options
            const getCommonOptions = (type) => {
                const theme = getThemeColors();
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart', // Smooth "generate from zero"
                    },
                    plugins: {
                        legend: {
                            display: type !== 'bar' && type !==
                            'horizontalBar', // Hide legend for standard bars
                            position: 'bottom',
                            labels: {
                                color: theme.text,
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: theme.tooltipBg,
                            titleColor: theme.tooltipText,
                            bodyColor: theme.tooltipText,
                            borderColor: theme.grid,
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true,
                            usePointStyle: true
                        }
                    },
                    scales: (type === 'doughnut' || type === 'pie') ? {} : {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: theme.text,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: theme.grid,
                                borderDash: [4, 4],
                                drawBorder: false
                            },
                            ticks: {
                                color: theme.text,
                                font: {
                                    size: 11
                                },
                                padding: 10
                            }
                        }
                    },
                    layout: {
                        padding: 10
                    }
                };
            };

            // --- CHART INSTANTIATION ---

            // 1. EVALUATION CHART (Progreso)
            const ctxEvaluacion = document.getElementById('chartEvaluacion');
            if (ctxEvaluacion) {
                const type = ctxEvaluacion.dataset.type === 'horizontalBar' ? 'bar' : (ctxEvaluacion.dataset.type ||
                    'bar');
                const indexAxis = ctxEvaluacion.dataset.type === 'horizontalBar' ? 'y' : 'x';
                const eventId = ctxEvaluacion.dataset.event;

                // Data Logic
                const eventosStats = @json($eventos_stats ?? []);
                let evaluados = {{ $proyectosEvaluados ?? 0 }};
                let pendientes = {{ $proyectosPendientes ?? 0 }};
                let total = evaluados + pendientes;

                if (eventId && eventosStats.length) {
                    const evt = eventosStats.find(e => e.id == eventId);
                    if (evt) {
                        evaluados = evt.evaluados;
                        pendientes = evt.pendientes;
                        total = evt.total;
                    }
                }

                let chartData = {
                    labels: ['Evaluados', 'Pendientes'],
                    datasets: [{
                        label: 'Proyectos',
                        data: [evaluados, pendientes],
                        backgroundColor: [colors.primary, colors.gray],
                        borderColor: [colors.primary, colors.gray],
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.6,
                    }]
                };

                // Specific Logic for Doughnut/Pie: 100% = Total
                if (type === 'doughnut' || type === 'pie') {
                    // Visual trick: Show Evaluated vs Pending to represent completion
                    chartData.datasets[0].backgroundColor = [colors.primary, isDark() ? colors.darkGray : colors
                        .lightGray
                    ];
                    chartData.datasets[0].borderColor = ['transparent', 'transparent'];
                    chartData.datasets[0].hoverOffset = 4;
                }

                new Chart(ctxEvaluacion, {
                    type: type,
                    data: chartData,
                    options: {
                        ...getCommonOptions(type),
                        indexAxis: indexAxis,
                        cutout: type === 'doughnut' ? '75%' : undefined,
                    }
                });
            }

            // 2. CARRERAS CHART
            const ctxCarreras = document.getElementById('chartCarreras');
            if (ctxCarreras) {
                const type = ctxCarreras.dataset.type === 'horizontalBar' ? 'bar' : (ctxCarreras.dataset.type ||
                    'doughnut');
                const indexAxis = ctxCarreras.dataset.type === 'horizontalBar' ? 'y' : 'x';
                const rawData = @json($participantesPorCarrera ?? []);
                const labels = Object.keys(rawData);
                const values = Object.values(rawData);

                new Chart(ctxCarreras, {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Participantes',
                            data: values,
                            backgroundColor: palette,
                            borderColor: isDark() ? '#1f2937' : '#ffffff',
                            borderWidth: 2,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        ...getCommonOptions(type),
                        indexAxis: indexAxis,
                        cutout: type === 'doughnut' ? '70%' : undefined,
                    }
                });
            }

            // 3. PENDIENTES ANUAL (Line Chart Fix)
            const ctxPendientes = document.getElementById('chartPendientesAnual');
            if (ctxPendientes) {
                const type = ctxPendientes.dataset.type === 'horizontalBar' ? 'bar' : (ctxPendientes.dataset.type ||
                    'line');
                const rawData = @json($pendientesAnual ?? []);

                // User wants DISTINCT COLORS for each year if it's a bar chart.
                // If it's a line chart, it must be a single color line.

                let bgColors, borderColors;

                if (type === 'line') {
                    bgColors = 'rgba(245, 158, 11, 0.2)'; // Orange transparent
                    borderColors = colors.warning; // Orange
                } else {
                    // For Bar/HorizontalBar, use the palette to give each bar a different color
                    // We need to repeat the palette if there are more bars than colors
                    bgColors = Object.keys(rawData).map((_, i) => palette[i % palette.length]);
                    borderColors = bgColors;
                }

                new Chart(ctxPendientes, {
                    type: type,
                    data: {
                        labels: Object.keys(rawData),
                        datasets: [{
                            label: 'Pendientes',
                            data: Object.values(rawData),
                            backgroundColor: bgColors,
                            borderColor: borderColors,
                            borderWidth: type === 'line' ? 3 : 0, // No border for bars if colorful
                            pointBackgroundColor: isDark() ? '#1f2937' : '#ffffff',
                            pointBorderColor: colors.warning,
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: type === 'line', // Fill area
                            tension: 0.4, // Smooth curve
                            borderRadius: 4
                        }]
                    },
                    options: {
                        ...getCommonOptions(type),
                        plugins: {
                            legend: {
                                display: false
                            } // Usually no legend needed for single series
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: getThemeColors().text,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    color: getThemeColors().grid
                                },
                                ticks: {
                                    color: getThemeColors().text
                                }
                            }
                        }
                    }
                });
            }

            // 4. CATEGORIAS CHART (New)
            const ctxCategorias = document.getElementById('chartCategorias');
            if (ctxCategorias) {
                const type = ctxCategorias.dataset.type === 'horizontalBar' ? 'bar' : (ctxCategorias.dataset.type ||
                    'bar');
                const indexAxis = ctxCategorias.dataset.type === 'horizontalBar' ? 'y' : 'x';
                // Fallback if empty
                const rawData = @json($categoriasData ?? []);
                const labels = Object.keys(rawData).length ? Object.keys(rawData) : ['Sin Datos'];
                const values = Object.keys(rawData).length ? Object.values(rawData) : [0];

                new Chart(ctxCategorias, {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Proyectos',
                            data: values,
                            backgroundColor: palette,
                            borderColor: (type === 'doughnut' || type === 'pie') ? 'transparent' : (
                                isDark() ? '#1f2937' : '#ffffff'),
                            borderWidth: 2,
                            borderRadius: 4,
                            barPercentage: 0.6,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        ...getCommonOptions(type),
                        indexAxis: indexAxis,
                        plugins: {
                            legend: {
                                display: type !== 'bar' && type !== 'horizontalBar',
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // --- 3. SAVE PREFERENCES ---
            const saveBtn = document.getElementById('saveConfigBtn');
            if (saveBtn) {
                saveBtn.addEventListener('click', savePreferences);
            }

            function savePreferences() {
                const allWidgets = [];
                document.querySelectorAll('.widget-visibility').forEach(input => {
                    const key = input.dataset.key;
                    const isVisible = input.checked;

                    // Get Type
                    const typeSelect = document.querySelector(`.widget-type[data-key="${key}"]`);
                    const type = typeSelect ? typeSelect.value : null;

                    // Get Event (if applicable)
                    const eventSelect = document.querySelector(`.widget-event[data-key="${key}"]`);
                    const eventId = eventSelect ? eventSelect.value : null;

                    // Find position in DOM
                    let position = 999;
                    const domItem = document.querySelector(`.widget-item[data-key="${key}"]`);
                    if (domItem) {
                        position = Array.from(grid.children).indexOf(domItem);
                    }

                    const settings = {};
                    if (type) settings.type = type;
                    if (eventId) settings.event_id = eventId;

                    allWidgets.push({
                        key: key,
                        position: position,
                        is_visible: isVisible,
                        settings: settings
                    });
                });

                // Sort by position
                allWidgets.sort((a, b) => a.position - b.position);

                fetch('{{ route('admin.dashboard.preferences') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            widgets: allWidgets
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        window.location.reload();
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>
</x-app-layout>
