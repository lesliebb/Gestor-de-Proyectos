<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Proyecto') }}
            </h2>
            <a href="{{ route('admin.proyectos.show', $proyecto) }}" 
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cancelar y Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative">
                
                {{-- Decoración Superior --}}
                <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 to-purple-600"></div>

                <div class="p-8">
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Información del Proyecto</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Actualiza los detalles técnicos y descriptivos visibles para los jueces.</p>
                    </div>

                    <form action="{{ route('admin.proyectos.update', $proyecto) }}" method="POST" class="space-y-6">
                        @csrf 
                        @method('PUT')
                        
                        {{-- Nombre del Proyecto --}}
                        <div>
                            <x-input-label for="nombre" value="Nombre del Proyecto" class="mb-2 font-bold" />
                            <div class="relative">
                                <x-text-input id="nombre" name="nombre" type="text" 
                                    class="w-full pl-4 pr-10 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all" 
                                    value="{{ old('nombre', $proyecto->nombre) }}" required autofocus />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        {{-- URL Repositorio --}}
                        <div>
                            <x-input-label for="repositorio_url" value="Enlace al Repositorio" class="mb-2 font-bold" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                </div>
                                <x-text-input id="repositorio_url" name="repositorio_url" type="url" 
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-indigo-500 font-mono text-sm" 
                                    placeholder="https://github.com/usuario/repositorio"
                                    value="{{ old('repositorio_url', $proyecto->repositorio_url) }}" />
                            </div>
                            <x-input-error :messages="$errors->get('repositorio_url')" class="mt-2" />
                        </div>
                        
                        {{-- Descripción --}}
                        <div>
                            <x-input-label for="descripcion" value="Descripción Detallada" class="mb-2 font-bold" />
                            <textarea name="descripcion" id="descripcion" rows="6"
                                class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-4 text-sm leading-relaxed placeholder-gray-400"
                                required placeholder="Describe el objetivo, alcance y tecnologías del proyecto...">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                        </div>

                        {{-- Footer con Acciones --}}
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('admin.proyectos.show', $proyecto) }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                Descartar Cambios
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Guardar Actualización
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>