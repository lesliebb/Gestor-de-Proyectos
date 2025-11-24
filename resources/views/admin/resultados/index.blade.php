<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Resultados y Ganadores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtro de Evento --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6 flex items-center gap-4">
                <span class="font-bold text-gray-700 dark:text-gray-300">Seleccionar Evento:</span>
                <form action="{{ route('admin.resultados.index') }}" method="GET">
                    <select name="evento_id" onchange="this.form.submit()"
                        class="border-gray-300 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300 rounded-md text-sm">
                        @foreach ($eventos as $ev)
                            <option value="{{ $ev->id }}" {{ $evento && $evento->id == $ev->id ? 'selected' : '' }}>
                                {{ $ev->nombre }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Tabla de Posiciones --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Tabla de Posiciones</h3>

                    @if ($ranking->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 italic">No hay datos suficientes para calcular resultados
                            en este
                            evento.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-800 dark:bg-gray-700 text-white dark:text-gray-300">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Lugar
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Puntaje
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Proyecto
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Equipo
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider">
                                            Constancias</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($ranking as $index => $item)
                                        @php
                                            $lugar = $index + 1;
                                            $filaClass = match ($lugar) {
                                                1 => 'bg-yellow-50 dark:bg-yellow-900/50 border-l-4 border-yellow-400',
                                                2 => 'bg-gray-50 dark:bg-gray-700/50 border-l-4 border-gray-400',
                                                3 => 'bg-orange-50 dark:bg-orange-900/50 border-l-4 border-orange-400',
                                                default => '',
                                            };
                                            $medalla = match ($lugar) {
                                                1 => '🥇',
                                                2 => '🥈',
                                                3 => '🥉',
                                                default => $lugar . '°',
                                            };
                                        @endphp
                                        <tr class="{{ $filaClass }}">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-xl font-bold text-gray-800 dark:text-gray-200">
                                                {{ $medalla }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap font-mono font-bold text-indigo-600 dark:text-indigo-400 text-lg">
                                                {{ $item->puntaje }} pts
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->nombre }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                {{ $item->equipo }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <a href="{{ route('admin.constancia.descargar', ['proyecto' => $item->id, 'posicion' => $index + 1]) }}"
                                                    target="_blank"
                                                    class="bg-indigo-600 text-white dark:bg-indigo-500 dark:hover:bg-indigo-400 px-3 py-1 rounded text-xs hover:bg-indigo-700 inline-block">
                                                    Generar PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>