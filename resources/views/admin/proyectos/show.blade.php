<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Evaluación de Proyecto') }}
            </h2>
            <a href="{{ route('admin.proyectos.index') }}"
                class="text-sm text-gray-500 dark:text-gray-400 underline hover:text-gray-900 dark:hover:text-gray-100">Volver</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- COLUMNA IZQUIERDA: Datos del Proyecto --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $proyecto->nombre }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">{{ $proyecto->descripcion }}</p>

                        <div class="border-t dark:border-gray-700 pt-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Equipo</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                {{ $proyecto->equipo->nombre }}</p>
                        </div>
                        <div class="mt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Repositorio</p>
                            <a href="{{ $proyecto->repositorio_url }}" target="_blank"
                                class="text-sm text-blue-600 dark:text-blue-400 break-all">{{ $proyecto->repositorio_url ?? 'No registrado' }}</a>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('admin.proyectos.edit', $proyecto) }}"
                                class="block w-full text-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 py-2 rounded text-sm">Editar
                                Datos</a>
                        </div>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: Desglose de Calificaciones --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div
                            class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-indigo-50 dark:bg-indigo-900/50">
                            <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-200">Resultados de Evaluación
                            </h3>
                            <div class="text-right">
                                <span
                                    class="block text-xs text-indigo-500 dark:text-indigo-400 uppercase font-bold">Puntaje
                                    Final</span>
                                <span
                                    class="text-3xl font-black {{ $puntajeTotal >= 70 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                    {{ number_format($puntajeTotal, 1) }}
                                </span>
                                <span class="text-sm text-gray-400 dark:text-gray-500">/ 100</span>
                            </div>
                        </div>

                        <div class="p-6">
                            @if(count($desglosePuntaje) > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Criterio</th>
                                                <th
                                                    class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Peso</th>
                                                <th
                                                    class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Promedio Jueces</th>
                                                <th
                                                    class="text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                                    Puntos Ganados</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                            @foreach($desglosePuntaje as $fila)
                                                <tr>
                                                    <td class="py-3 text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $fila['criterio'] }}</td>
                                                    <td class="py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $fila['ponderacion'] }}%</td>
                                                    <td
                                                        class="py-3 text-center text-sm font-bold text-blue-600 dark:text-blue-400">
                                                        {{ $fila['promedio_jueces'] }}</td>
                                                    <td
                                                        class="py-3 text-right text-sm font-black text-gray-800 dark:text-gray-100">
                                                        {{ $fila['puntos_reales'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div
                                    class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/50 rounded text-xs text-yellow-800 dark:text-yellow-300">
                                    <strong>Nota:</strong> El "Promedio Jueces" es la media de todas las calificaciones
                                    recibidas para ese criterio (escala 0-100). Los "Puntos Ganados" son el resultado de
                                    aplicar la ponderación.
                                </div>
                            @else
                                <div class="text-center py-10 text-gray-400 dark:text-gray-500">
                                    Aún no hay criterios definidos o calificaciones registradas.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>