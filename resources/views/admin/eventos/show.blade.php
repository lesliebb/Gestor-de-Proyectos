<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalles del Evento') }}
            </h2>
            <a href="{{ route('admin.eventos.index') }}"
                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">
                ← Volver a la lista
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- COLUMNA IZQUIERDA: Detalles del Evento --}}
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 border-b dark:border-gray-700 pb-2">
                            Información General</h3>

                        <div class="mb-4">
                            <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase">Nombre</span>
                            <span
                                class="block text-gray-800 dark:text-gray-200 font-medium">{{ $evento->nombre }}</span>
                        </div>

                        <div class="mb-4">
                            <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase">Descripción</span>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $evento->descripcion ?? 'Sin descripción' }}</p>
                        </div>

                        <div class="mb-4">
                            <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase">Estado</span>
                            @if (\Carbon\Carbon::now()->between($evento->fecha_inicio, $evento->fecha_fin))
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">En
                                    curso</span>
                            @elseif(\Carbon\Carbon::now()->lt($evento->fecha_inicio))
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">Próximo</span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Finalizado</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm dark:text-gray-300">
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase">Inicio</span>
                                {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 uppercase">Fin</span>
                                {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t dark:border-gray-700">
                            <a href="{{ route('admin.eventos.edit', $evento) }}"
                                class="block w-full text-center px-4 py-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 rounded text-sm hover:bg-gray-700 dark:hover:bg-white">Editar
                                Información</a>
                        </div>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: Criterios de Evaluación --}}
                <div class="md:col-span-2" x-data="{ open: false }">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                        {{-- Encabezado y Botón --}}
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Criterios de Evaluación</h3>
                            <button @click="open = !open"
                                class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 px-3 py-1 rounded text-sm hover:bg-indigo-200 dark:hover:bg-indigo-800 transition">
                                <span x-show="!open">+ Agregar Criterio</span>
                                <span x-show="open">Cancelar</span>
                            </button>
                        </div>

                        {{-- Formulario Desplegable (Alpine) --}}
                        <div x-show="open"
                            class="mb-6 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-indigo-100 dark:border-gray-700"
                            style="display: none;">
                            <form action="{{ route('admin.eventos.criterios.store', $evento) }}" method="POST"
                                class="flex gap-2 items-end">
                                @csrf
                                <div class="flex-1">
                                    <x-input-label for="nombre_criterio" value="Nombre (ej. Innovación)" />
                                    <x-text-input id="nombre_criterio" name="nombre" type="text"
                                        class="block w-full h-9 text-sm" required />
                                </div>
                                <div class="w-24">
                                    <x-input-label for="ponderacion" value="Valor %" />
                                    <x-text-input id="ponderacion" name="ponderacion" type="number" min="1" max="100"
                                        class="block w-full h-9 text-sm" required />
                                </div>
                                <button type="submit"
                                    class="bg-indigo-600 dark:bg-indigo-500 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-500 dark:hover:bg-indigo-400 h-9">
                                    Guardar
                                </button>
                            </form>
                            <x-input-error :messages="$errors->get('ponderacion')" class="mt-2" />
                        </div>

                        {{-- Tabla de Criterios --}}
                        @if ($evento->criterios->isEmpty())
                            <div
                                class="text-center py-8 bg-gray-50 dark:bg-gray-900 rounded-lg border-2 border-dashed border-gray-200 dark:border-gray-700">
                                <p class="text-gray-500 dark:text-gray-400 mb-2">No hay criterios definidos.</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Define cómo serán evaluados los
                                    proyectos.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Criterio</th>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Ponderación</th>
                                            <th
                                                class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($evento->criterios as $criterio)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $criterio->nombre }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">
                                                    {{ $criterio->ponderacion }}%
                                                </td>
                                                <td class="px-4 py-2 text-right text-sm space-x-2">

                                                    {{-- 1. Botón EDITAR (Faltaba esto) --}}
                                                    <a href="{{ route('admin.criterios.edit', $criterio) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                                        Editar
                                                    </a>

                                                    {{-- 2. Separador visual (opcional) --}}
                                                    <span class="text-gray-300 dark:text-gray-600">|</span>

                                                    {{-- 3. Botón ELIMINAR --}}
                                                    <form action="{{ route('admin.criterios.destroy', $criterio) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium"
                                                            onclick="return confirm('¿Estás seguro de borrar este criterio?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- Fila de Total --}}
                                        <tr class="bg-gray-50 dark:bg-gray-700 font-bold">
                                            <td class="px-4 py-2 text-sm text-right dark:text-gray-300">TOTAL:</td>
                                            <td
                                                class="px-4 py-2 text-sm {{ $evento->criterios->sum('ponderacion') != 100 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                                {{ $evento->criterios->sum('ponderacion') }}%
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @if ($evento->criterios->sum('ponderacion') != 100)
                                <p class="text-xs text-red-500 dark:text-red-400 mt-2 font-semibold">* La suma debe ser 100%
                                    exacto.</p>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>