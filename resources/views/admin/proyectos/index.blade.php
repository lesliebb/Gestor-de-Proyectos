<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Proyectos Registrados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtros --}}
            <div
                class="bg-white dark:bg-gray-800 p-4 border-b border-gray-200 dark:border-gray-700 flex gap-4 rounded-t-lg">
                <form action="{{ route('admin.proyectos.index') }}" method="GET" class="flex w-full gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar proyecto..."
                        class="border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white rounded-md text-sm w-1/3">
                    <select name="evento_id"
                        class="border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white rounded-md text-sm w-1/4">
                        <option value="">Todos los eventos</option>
                        @foreach($eventos as $ev)
                            <option value="{{ $ev->id }}" {{ request('evento_id') == $ev->id ? 'selected' : '' }}>
                                {{ $ev->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white px-4 rounded-md text-sm">Filtrar</button>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-b-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    Proyecto</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    Equipo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    Evento</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($proyectos as $proyecto)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $proyecto->nombre }}</div>
                                        @if($proyecto->repositorio_url)
                                            <a href="{{ $proyecto->repositorio_url }}" target="_blank"
                                                class="text-xs text-blue-500 dark:text-blue-400 hover:underline">Ver Repo</a>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $proyecto->equipo->nombre }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $proyecto->evento->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <a href="{{ route('admin.proyectos.show', $proyecto) }}"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">Evaluar/Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-white dark:bg-gray-800">{{ $proyectos->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>