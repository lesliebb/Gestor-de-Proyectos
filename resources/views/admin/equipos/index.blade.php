<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Supervisión de Equipos') }}
            </h2>
            <a href="{{ route('admin.equipos.create') }}"
                class="bg-indigo-600 dark:bg-indigo-500 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-500 dark:hover:bg-indigo-400 shadow">
                + Crear Equipo Manualmente
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensajes Flash --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 dark:bg-green-900 dark:border-green-600 dark:text-green-300 p-4 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- BARRA DE FILTROS --}}
            <div
                class="bg-white dark:bg-gray-800 p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row gap-4 justify-between items-center rounded-t-lg">
                <form action="{{ route('admin.equipos.index') }}" method="GET"
                    class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">

                    {{-- Buscador por Nombre --}}
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full sm:w-64 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                            placeholder="Buscar equipo...">
                    </div>

                    {{-- Filtro por Evento --}}
                    <select name="evento_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:w-48 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                        <option value="">Todos los eventos</option>
                        @foreach ($eventos as $evento)
                            <option value="{{ $evento->id }}"
                                {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                                {{ $evento->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                        class="text-white bg-indigo-700 hover:bg-indigo-800 dark:bg-indigo-600 dark:hover:bg-indigo-700 font-medium rounded-lg text-sm px-4 py-2">
                        Filtrar
                    </button>

                    @if (request('search') || request('evento_id'))
                        <a href="{{ route('admin.equipos.index') }}"
                            class="text-gray-700 bg-white border border-gray-300 font-medium rounded-lg text-sm px-4 py-2 hover:bg-gray-100 dark:text-white dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-b-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Equipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Evento / Proyecto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Integrantes</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($equipos as $equipo)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $equipo->nombre }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Creado:
                                            {{ $equipo->created_at->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($equipo->proyecto)
                                            <div class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">
                                                {{ $equipo->proyecto->nombre }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $equipo->proyecto->evento->nombre ?? 'Evento eliminado' }}</div>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                Sin Proyecto
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $equipo->participantes->count() }} miembros
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.equipos.show', $equipo) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Gestionar</a>
                                        <a href="{{ route('admin.equipos.edit', $equipo) }}"
                                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 mr-3">Renombrar</a>

                                        <form action="{{ route('admin.equipos.destroy', $equipo) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="return confirm('¿Eliminar este equipo? Los alumnos quedarán libres.')">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-white dark:bg-gray-800">
                    {{ $equipos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
