<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $equipo->nombre }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Detalles del Equipo y Proyecto</p>
            </div>
            <a href="{{ route('participante.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Información del Proyecto -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Proyecto</h3>
                
                @if($equipo->proyecto)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Nombre:</p>
                            <p class="text-gray-900 dark:text-white">{{ $equipo->proyecto->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Descripción:</p>
                            <p class="text-gray-900 dark:text-white">{{ $equipo->proyecto->descripcion }}</p>
                        </div>
                        @if($equipo->proyecto->repositorio_url)
                            <div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Repositorio:</p>
                                <a href="{{ $equipo->proyecto->repositorio_url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $equipo->proyecto->repositorio_url }}
                                </a>
                            </div>
                        @endif
                        @if($equipo->proyecto->evento)
                            <div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Evento:</p>
                                <p class="text-gray-900 dark:text-white">{{ $equipo->proyecto->evento->nombre }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No hay proyecto asociado</p>
                @endif
            </div>

            <!-- Miembros del Equipo -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Miembros del Equipo</h3>
                
                @if($equipo->participantes->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($equipo->participantes as $participante)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $participante->user->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $participante->user->email }}</p>
                                    @if($participante->carrera)
                                        <p class="text-sm text-gray-500 dark:text-gray-500">{{ $participante->carrera->nombre }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                        {{ $participante->pivot->perfil_id == 3 ? 'Líder' : 'Miembro' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No hay miembros en el equipo</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
