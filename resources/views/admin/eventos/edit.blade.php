<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Evento') }}
            </h2>
            <a href="{{ route('admin.eventos.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cancelar
            </a>
        </div>
    </x-slot>

    {{-- Preparación de datos para AlpineJS --}}
    @php
        $judgesData = $jueces->map(function($j) {
            return [
                'id' => $j->id,
                'name' => $j->name,
                'email' => $j->email,
                'initial' => strtoupper(substr($j->name, 0, 1))
            ];
        });
        
        // IDs seleccionados actualmente (desde DB o si falló la validación desde old input)
        $currentSelection = old('jueces', $evento->jueces->pluck('id')->toArray());
    @endphp

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-visible relative">
                
                {{-- Decoración Superior --}}
                <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 to-purple-600"></div>

                <div class="p-8">
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Información del Evento</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Actualiza las fechas y asigna el panel de jueces.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.eventos.update', $evento) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Nombre --}}
                        <div>
                            <x-input-label for="nombre" value="Nombre del Evento" class="mb-2 font-bold" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                </div>
                                <x-text-input id="nombre" name="nombre" type="text" 
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm" 
                                    :value="old('nombre', $evento->nombre)" required autofocus />
                            </div>
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <x-input-label for="descripcion" value="Descripción" class="mb-2 font-bold" />
                            <textarea id="descripcion" name="descripcion" rows="4"  required
                                class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-4 text-sm leading-relaxed placeholder-gray-400 transition-all"
                                placeholder="Detalles...">{{ old('descripcion', $evento->descripcion) }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                        </div>

                        {{-- SECCIÓN DE JUECES CON BUSCADOR MULTI-SELECT --}}
                        <div x-data="judgeSelector({{ $judgesData->toJson() }}, {{ json_encode($currentSelection) }})" class="relative">
                            <x-input-label for="jueces" value="Asignar Jueces" class="mb-2 font-bold" />
                            
                            {{-- 1. Lista de Jueces Seleccionados (Visual) --}}
                            <div class="mb-3 flex flex-wrap gap-2 min-h-[40px] p-2 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-600">
                                <template x-for="juez in selectedJudgesList" :key="juez.id">
                                    <div class="flex items-center gap-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 pl-2 pr-1 py-1 rounded-lg shadow-sm group">
                                        <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[10px] font-bold" x-text="juez.initial"></div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300" x-text="juez.name"></span>
                                        <button type="button" @click="remove(juez.id)" class="p-1 hover:bg-red-50 dark:hover:bg-red-900/30 text-gray-400 hover:text-red-500 rounded transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>
                                <div x-show="selectedIds.length === 0" class="w-full text-center py-2">
                                    <span class="text-xs text-gray-400 italic">No hay jueces asignados aún.</span>
                                </div>
                            </div>

                            {{-- 2. Input Buscador --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" x-model="search" placeholder="Buscar juez por nombre o correo..." 
                                       class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                            </div>

                            {{-- 3. Dropdown de Resultados --}}
                            <div x-show="search.length > 0 && filteredJudges.length > 0" 
                                 class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto"
                                 style="display: none;"
                                 x-transition.opacity.duration.200ms>
                                <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                    <template x-for="juez in filteredJudges" :key="juez.id">
                                        <li class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer" 
                                            @click="add(juez.id)">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400" x-text="juez.initial"></div>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-800 dark:text-gray-200" x-text="juez.name"></div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400" x-text="juez.email"></div>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <div x-show="search.length > 0 && filteredJudges.length === 0" class="mt-2 p-2 text-center text-xs text-gray-500 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700">
                                No se encontraron coincidencias.
                            </div>

                            {{-- 4. Inputs Ocultos para enviar al Backend --}}
                            {{-- Esto crea un <input name="jueces[]" value="ID"> por cada juez seleccionado --}}
                            <template x-for="id in selectedIds" :key="id">
                                <input type="hidden" name="jueces[]" :value="id">
                            </template>

                            <x-input-error :messages="$errors->get('jueces')" class="mt-2" />
                        </div>


                        {{-- Fechas (Grid 2 Columnas) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Fecha Inicio --}}
                            <div>
                                <x-input-label for="fecha_inicio" value="Inicio (Fecha y Hora)" class="mb-2 font-bold" />
                                <div class="relative">
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" 
                                        value="{{ old('fecha_inicio', \Carbon\Carbon::parse($evento->fecha_inicio)->format('Y-m-d')) }}"
                                        min="{{ now()->format('Y-m-d') }}"
                                        class="w-full pl-4 pr-4 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm shadow-sm cursor-pointer" 
                                        required />
                                </div>
                                <x-input-error :messages="$errors->get('fecha_inicio')" class="mt-2" />
                            </div>

                            {{-- Fecha Fin --}}
                            <div>
                                <x-input-label for="fecha_fin" value="Cierre (Fecha y Hora)" class="mb-2 font-bold" />
                                <div class="relative">
                                    <input type="date" id="fecha_fin" name="fecha_fin" 
                                        value="{{ old('fecha_fin', \Carbon\Carbon::parse($evento->fecha_fin)->format('Y-m-d')) }}"
                                        min="{{ now()->format('Y-m-d') }}"
                                        class="w-full pl-4 pr-4 py-3 rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm shadow-sm cursor-pointer" 
                                        required />
                                </div>
                                <x-input-error :messages="$errors->get('fecha_fin')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('admin.eventos.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                Descartar
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script AlpineJS para el Multiselect --}}
    <script>
        function judgeSelector(allJudges, initiallySelectedIds) {
            return {
                search: '',
                allJudges: allJudges, // Array con todos los jueces disponibles
                selectedIds: initiallySelectedIds, // Array de IDs seleccionados [1, 5, 8]

                // Devuelve los objetos completos de los jueces seleccionados para mostrarlos en las etiquetas
                get selectedJudgesList() {
                    return this.allJudges.filter(j => this.selectedIds.includes(j.id));
                },

                // Filtra jueces por búsqueda Y que NO estén ya seleccionados
                get filteredJudges() {
                    if (this.search === '') return [];
                    const query = this.search.toLowerCase();
                    return this.allJudges.filter(j => 
                        (j.name.toLowerCase().includes(query) || j.email.toLowerCase().includes(query)) &&
                        !this.selectedIds.includes(j.id)
                    );
                },

                add(id) {
                    if (!this.selectedIds.includes(id)) {
                        this.selectedIds.push(id);
                    }
                    this.search = ''; // Limpiar buscador tras seleccionar
                },

                remove(id) {
                    this.selectedIds = this.selectedIds.filter(i => i !== id);
                }
            }
        }
    </script>
</x-app-layout>