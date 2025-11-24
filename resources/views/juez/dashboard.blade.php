<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sala de Jueces') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-indigo-500 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <div
                                class="text-gray-500 dark:text-gray-400 text-xs uppercase font-bold mb-1 tracking-wider">
                                Total Proyectos</div>
                            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $totalProyectos }}</div>
                        </div>
                        <div
                            class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <div
                                class="text-gray-500 dark:text-gray-400 text-xs uppercase font-bold mb-1 tracking-wider">
                                Calificados</div>
                            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $proyectosEvaluados }}
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <div
                                class="text-gray-500 dark:text-gray-400 text-xs uppercase font-bold mb-1 tracking-wider">
                                Pendientes</div>
                            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $pendientes }}</div>
                        </div>
                        <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Eventos Activos</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($eventos as $evento)
                        <div
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ $evento->nombre }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
                                            {{ $evento->descripcion }}</p>
                                    </div>
                                    <span
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-2 py-1 rounded">
                                        {{ $evento->proyectos_count }} Equipos
                                    </span>
                                </div>

                                <div class="mt-6 flex items-center justify-between">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Cierre: {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}
                                    </div>
                                    <a href="{{ route('juez.evento.show', $evento) }}"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-bold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition">
                                        Entrar a Evaluar &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>