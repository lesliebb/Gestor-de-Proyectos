<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bitácora de Seguimiento') }}
            </h2>
            <span class="px-3 py-1 text-xs font-bold rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">
                {{ $avances->count() }} Registros Totales
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- COLUMNA IZQUIERDA: FORMULARIO (Sticky) --}}
                <div class="lg:col-span-4 order-last lg:order-first">
                    <div class="bg-white dark:bg-gray-800 shadow-lg shadow-gray-200/50 dark:shadow-none rounded-2xl p-6 sticky top-8 border border-gray-100 dark:border-gray-700">
                        
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl text-indigo-600 dark:text-indigo-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Nuevo Registro</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Documenta el progreso diario.</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('participante.avances.store') }}">
                            @csrf
                            
                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Fecha de Registro</label>
                                <div class="flex items-center px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-600 dark:text-gray-300 text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ now()->isoFormat('dddd D [de] MMMM') }}
                                </div>
                            </div>

                            <div class="mb-5 relative">
                                <label for="descripcion" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Detalles del Avance</label>
                                <textarea id="descripcion" name="descripcion" rows="6" 
                                          class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm leading-relaxed p-4 shadow-sm transition placeholder-gray-400 text-gray-900 dark:text-white" 
                                          placeholder="Describe qué lograron hoy:&#10;• Módulos terminados&#10;• Errores corregidos&#10;• Decisiones tomadas..." required></textarea>
                                <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                            </div>

                            <button type="submit" class="w-full group relative flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-md hover:shadow-lg">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </span>
                                Publicar Avance
                            </button>
                        </form>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: TIMELINE (Feed) --}}
                <div class="lg:col-span-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Línea de Tiempo
                        </h3>
                        <span class="text-xs text-gray-500">Ordenado por fecha (Reciente primero)</span>
                    </div>

                    @if($avances->count() > 0)
                        <div class="space-y-0 relative">
                            <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                            @foreach($avances as $avance)
                                <div class="relative pl-16 py-2 group">
                                    
                                    <div class="absolute left-0 top-3 w-12 h-12 rounded-full border-4 border-gray-50 dark:border-gray-900 bg-white dark:bg-gray-800 shadow-sm z-10 flex items-center justify-center">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-500 dark:text-indigo-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-800 transition-all relative">
                                        
                                        <div class="absolute top-6 -left-2 w-4 h-4 bg-white dark:bg-gray-800 border-l border-b border-gray-100 dark:border-gray-700 transform rotate-45"></div>

                                        <div class="flex justify-between items-start mb-2 relative z-10">
                                            <div>
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 text-lg">
                                                        {{ \Carbon\Carbon::parse($avance->fecha)->locale('es')->isoFormat('D [de] MMMM') }}
                                                    </h4>
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-300">
                                                        {{ $avance->created_at->format('H:i A') }}
                                                    </span>
                                                </div>
                                                </div>

                                            <form action="{{ route('participante.avances.destroy', $avance->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este registro?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar registro">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-300 mt-2">
                                            <p class="whitespace-pre-line leading-relaxed">{{ $avance->descripcion }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="pl-16 pt-4 pb-2">
                                <div class="flex items-center gap-2 text-gray-400 text-xs uppercase tracking-widest font-bold">
                                    <div class="w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-700"></div>
                                    Inicio del Proyecto
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Bitácora Vacía</h3>
                            <p class="text-gray-500 text-sm max-w-xs text-center mt-2">No hay registros aún. Usa el formulario de la izquierda para documentar su primer logro.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>