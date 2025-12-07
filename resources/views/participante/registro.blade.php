<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            {{ __('Completar Perfil de Participante') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            {{-- Tarjeta Principal del Formulario --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl">
                
                {{-- Encabezado de la Tarjeta --}}
                <div class="px-8 py-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Información Académica Requerida</h3>
                            <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                Para acceder al panel de eventos y equipos, necesitamos que completes tu registro con tus datos escolares y de contacto.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Cuerpo del Formulario --}}
                <div class="p-8">
                    <form method="POST" action="{{ route('participante.registro.store') }}" class="space-y-6">
                        @csrf

                        {{-- Campo: Número de Control / Matrícula --}}
                        <div>
                            <x-input-label for="no_control" :value="__('Número de Control / Matrícula')" class="text-base font-medium pl-1" />
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .6.4 1 1 1s1-.4 1-1m0 0H9m4 0h1m-5 4h6m-6 4h6"></path></svg>
                                </div>
                                <x-text-input id="no_control" 
                                              class="block w-full pl-12 py-3 text-base bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent rounded-xl transition-all" 
                                              type="text" 
                                              name="no_control" 
                                              :value="old('no_control', $perfil->no_control ?? '')" 
                                              required 
                                              autofocus 
                                              autocomplete="off" 
                                              placeholder="Ej: 202103456" />
                            </div>
                            <x-input-error :messages="$errors->get('no_control')" class="mt-2 pl-1" />
                        </div>

                        @php
                            $carrerasData = $carreras->map(function($c) {
                                return ['id' => $c->id, 'name' => $c->nombre];
                            });
                            $currentCarrera = old('carrera_id', $perfil->carrera_id ?? '');
                        @endphp
                        {{-- Campo: Carrera (Con buscador) --}}
                        <div x-data="carreraSelector({{ $carrerasData->toJson() }}, '{{ $currentCarrera }}')" class="relative">
                            
                            <x-input-label for="carrera_id_trigger" :value="__('Carrera')" class="text-base font-medium pl-1" />
                            
                            {{-- Input oculto para enviar el dato en el formulario --}}
                            <input type="hidden" name="carrera_id" x-model="selectedId" required>

                            <div class="relative mt-2">
                                {{-- Icono izquierdo --}}
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-500 z-10">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>

                                {{-- Trigger (El botón que parece un input) --}}
                                <button type="button"
                                        id="carrera_id_trigger"
                                        @click="open = !open"
                                        class="relative w-full pl-12 pr-10 py-3 text-left text-base bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                                        :class="{'ring-2 ring-indigo-500 border-transparent': open}">
                                    <span x-text="selectedName"
                                          :class="selectedId ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500'">
                                    </span>
                                </button>

                                {{-- Icono derecho (Flecha animada) --}}
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                {{-- Menú Desplegable con Buscador --}}
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="absolute z-50 mt-2 w-full rounded-xl bg-white dark:bg-[#1a222c] shadow-xl border border-gray-100 dark:border-gray-700"
                                     style="display: none;">
                                    
                                     {{-- Input de Búsqueda --}}
                                     <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                                         <input type="text" x-ref="search" x-model.debounce.300ms="search" placeholder="Buscar carrera..."
                                                class="w-full px-3 py-2 text-base bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-600 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-all"
                                                @click.stop>
                                     </div>

                                    {{-- Lista de Opciones --}}
                                    <div class="py-2 max-h-52 overflow-auto focus:outline-none scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                                        <template x-for="option in filteredCarreras" :key="option.id">
                                            <div @click="select(option)"
                                                 class="cursor-pointer select-none relative py-3 pl-4 pr-9 text-gray-900 dark:text-gray-100 hover:bg-indigo-50 dark:hover:bg-indigo-900/50 transition-colors"
                                                 :class="{'bg-indigo-100 dark:bg-indigo-900/80 text-indigo-900 dark:text-white font-medium': selectedId == option.id}">
                                                <span x-text="option.name" class="block truncate"></span>
                                                
                                                {{-- Checkmark icon for selected item --}}
                                                <span x-show="selectedId == option.id" class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600 dark:text-indigo-400">
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </template>
                                        {{-- Mensaje si no hay resultados --}}
                                        <div x-show="filteredCarreras.length === 0" class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No se encontraron carreras.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('carrera_id')" class="mt-2 pl-1" />
                        </div>

                        {{-- Campo: Teléfono --}}
                        <div>
                            <x-input-label for="telefono" :value="__('Teléfono')" class="text-base font-medium pl-1" />
                            <div class="relative mt-2">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <x-text-input id="telefono" 
                                              class="block w-full pl-12 py-3 text-base bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent rounded-xl transition-all" 
                                              type="tel" 
                                              name="telefono" 
                                              :value="old('telefono', $perfil->telefono ?? '')" 
                                              placeholder="Ej: 951 123 4567"
                                              required />
                            </div>
                            <p class="mt-2 pl-1 text-sm flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Necesario para contacto urgente durante el evento.
                            </p>
                            <x-input-error :messages="$errors->get('telefono')" class="mt-2 pl-1" />
                        </div>

                        {{-- Botón de Acción --}}
                        <div class="flex items-center justify-end pt-6">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-bold text-base text-white tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                {{ __('Guardar y Continuar') }}
                                <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Script AlpineJS para el selector de carrera --}}
    <script>
        function carreraSelector(allCarreras, initialId) {
            return {
                search: '',
                open: false,
                allCarreras: allCarreras,
                selectedId: initialId,

                init() {
                    this.$watch('open', isOpen => {
                        if (isOpen) {
                            // Focuses the search input when dropdown opens
                            this.$nextTick(() => {
                                this.$refs.search.focus();
                            });
                        }
                    });
                },

                get selectedName() {
                    if (!this.selectedId) return 'Selecciona tu carrera';
                    const found = this.allCarreras.find(c => c.id == this.selectedId);
                    return found ? found.name : 'Selecciona tu carrera';
                },

                get filteredCarreras() {
                    if (this.search.trim() === '') {
                        return this.allCarreras;
                    }
                    const query = this.search.toLowerCase();
                    return this.allCarreras.filter(carrera =>
                        carrera.name.toLowerCase().includes(query)
                    );
                },

                select(option) {
                    this.selectedId = option.id;
                    this.open = false;
                    this.search = ''; // Limpia la búsqueda al seleccionar
                }
            }
        }
    </script>
</x-app-layout>