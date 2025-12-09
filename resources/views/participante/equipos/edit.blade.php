<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Configuración del Equipo') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="teamManager({{ $candidatos }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- MODAL DE INVITACIÓN --}}
            <div x-show="showInviteModal" @keydown.escape="closeInviteModal()" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70" @click="closeInviteModal()"></div>

                {{-- Modal --}}
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full border border-gray-100 dark:border-gray-700 z-10" @click.stop x-transition>
                    
                    {{-- Header --}}
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 flex justify-between items-center rounded-t-2xl">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            Enviar Invitación
                        </h3>
                        <button @click="closeInviteModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 py-4">
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <strong>Candidato:</strong>
                            </p>
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-100 dark:border-indigo-800">
                                <p class="text-sm font-bold text-indigo-900 dark:text-indigo-300" x-text="selectedName"></p>
                            </div>
                        </div>

                        <form id="inviteForm" method="POST" :action="`{{ route('participante.invitaciones.enviar', $equipo) }}`" class="space-y-4">
                            @csrf
                            <input type="hidden" name="participante_id" x-model="selectedId">

                            {{-- Rol Sugerido --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Rol Sugerido (Opcional)
                                </label>
                                <select name="perfil_sugerido_id" x-model="suggestedRole"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                    <option value="">-- Sin especificar --</option>
                                    @foreach($perfiles as $perfil)
                                        <option value="{{ $perfil->id }}">{{ $perfil->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Mensaje --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Mensaje Personal (Opcional)
                                </label>
                                <textarea name="mensaje" x-model="inviteMessage" rows="4"
                                          placeholder="Cuéntale por qué lo invitas a tu equipo..."
                                          maxlength="500"
                                          class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"></textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <span x-text="inviteMessage.length"></span>/500 caracteres
                                </p>
                            </div>
                        </form>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 flex gap-3 justify-end rounded-b-2xl">
                        <button @click="closeInviteModal()" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancelar
                        </button>
                        <button @click="sendInvitation()" 
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition shadow-lg shadow-indigo-500/30">
                            ✓ Enviar Invitación
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- ================================================= --}}
                {{-- COLUMNA IZQUIERDA: GESTIÓN DE MIEMBROS (4 cols) --}}
                {{-- ================================================= --}}
                <div class="lg:col-span-4 space-y-6">
                    
                    {{-- 1. CARD DE ESTADO (KPI) --}}
                    @php
                        $totalMiembros = $equipo->participantes->count();
                        $carreras = $equipo->participantes->pluck('carrera_id')->unique();
                        $esMultidisciplinario = $carreras->count() > 1;
                        $teamComplete = $totalMiembros >= 2;
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-{{ $teamComplete ? 'green' : 'yellow' }}-100 dark:bg-opacity-10 rounded-full blur-xl -mr-10 -mt-10"></div>
                        
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Composición del Equipo</h3>
                        <div class="flex items-end gap-2 mb-4">
                            <span class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $totalMiembros }}</span>
                            <span class="text-sm font-medium text-gray-500 mb-1">/ 5 Miembros</span>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-sm {{ $teamComplete ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $teamComplete ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' }}"></path></svg>
                                <span class="font-medium">{{ $teamComplete ? 'Mínimo alcanzado' : 'Faltan integrantes (Mín. 2)' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm {{ $esMultidisciplinario ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                <span class="font-medium">{{ $esMultidisciplinario ? 'Equipo Multidisciplinario' : 'Falta diversidad de carreras' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. CARD DE MIEMBROS --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-visible">
                        
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/20 rounded-t-2xl">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide mb-4">Reclutar Talento</h3>
                            
                            @if($totalMiembros < 5)
                                <div class="relative" x-data="{ open: false }">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <input type="text" 
                                               x-model="search" 
                                               @focus="open = true"
                                               @click.away="open = false"
                                               placeholder="Buscar por nombre..." 
                                               class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                                    </div>
                                    
                                    <div x-show="search.length > 0 && filteredParticipants.length > 0" 
                                         class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto"
                                         style="display: none;"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100">
                                        <template x-for="p in filteredParticipants" :key="p.id">
                                            <div @click="selectParticipant(p); open = false" 
                                                 class="px-4 py-3 hover:bg-indigo-50 dark:hover:bg-gray-700 cursor-pointer border-b last:border-0 border-gray-50 dark:border-gray-700 flex justify-between items-center group">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600" x-text="p.name"></p>
                                                    <p class="text-[10px] text-gray-500 uppercase" x-text="p.carrera"></p>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </div>
                                        </template>
                                    </div>

                                    <div x-show="selectedId !== null" x-transition class="mt-4 bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider">Candidato Seleccionado</p>
                                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100" x-text="selectedName"></p>
                                            </div>
                                            <button type="button" @click="resetSelection()" class="text-gray-400 hover:text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>

                                        <button type="button" @click="openInviteModal()" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-widest shadow-md hover:shadow-lg transition-all">
                                            Enviar Invitación
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 text-center">
                                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Equipo Completo</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-4 space-y-1">
                            @foreach($equipo->participantes as $miembro)
                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                            {{ substr($miembro->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 dark:text-white leading-tight">{{ $miembro->user->name }}</p>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-500 px-1.5 py-0.5 rounded">{{ $miembro->carrera->nombre ?? 'N/A' }}</span>
                                                <span class="text-[10px] text-indigo-600 dark:text-indigo-400 font-bold">
                                                    {{ \App\Models\Perfil::find($miembro->pivot->perfil_id)->nombre ?? 'Rol' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    @if(Auth::user()->participante->id !== $miembro->id)
                                        <form action="{{ route('participante.equipos.removeMember', $miembro->id) }}" method="POST" onsubmit="return confirm('¿Sacar del equipo?');">
                                            @csrf @method('DELETE')
                                            <button class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mr-2">Tú</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ================================================= --}}
                {{-- COLUMNA DERECHA: PROYECTO (8 cols) --}}
                {{-- ================================================= --}}
                <div class="lg:col-span-8">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                        
                        <div class="p-8 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Detalles del Proyecto</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Actualiza la información visible para los jueces.</p>
                        </div>

                        <div class="p-8">
                            @if(session('success'))
                                <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 text-green-700 dark:text-green-300 text-sm font-medium flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-700 dark:text-red-300 text-sm font-medium">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('participante.equipos.update', $equipo->id) }}" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="nombre" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nombre del Equipo</label>
                                        <div class="relative">
                                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $equipo->nombre) }}" required
                                                class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all pl-4 pr-10 py-3 text-sm">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                                    </div>

                                    <div>
                                        <label for="nombre_proyecto" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Título del Proyecto</label>
                                        <div class="relative">
                                            <input type="text" id="nombre_proyecto" name="nombre_proyecto" value="{{ old('nombre_proyecto', $equipo->proyecto->nombre ?? '') }}" required
                                                class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all pl-4 pr-10 py-3 text-sm">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('nombre_proyecto')" class="mt-2" />
                                    </div>
                                </div>

                                <div>
                                    <label for="repositorio_url" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Repositorio de Código</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                        </div>
                                        <input type="url" id="repositorio_url" name="repositorio_url" value="{{ old('repositorio_url', $equipo->proyecto->repositorio_url ?? '') }}" placeholder="https://github.com/usuario/repo"
                                            class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all pl-10 pr-4 py-3 text-sm font-mono">
                                    </div>
                                    <x-input-error :messages="$errors->get('repositorio_url')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="descripcion_proyecto" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Descripción General</label>
                                    <textarea id="descripcion_proyecto" name="descripcion_proyecto" rows="6" 
                                        class="w-full rounded-xl border-gray-300 dark:bg-gray-900 dark:border-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all p-4 text-sm leading-relaxed placeholder-gray-400"
                                        placeholder="Explica de qué trata tu proyecto en pocas palabras...">{{ old('descripcion_proyecto', $equipo->proyecto->descripcion ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('descripcion_proyecto')" class="mt-2" />
                                </div>

                                <div class="flex justify-end pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/30">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                        Guardar Cambios
                                    </button>
                                </div>
                            </form>

                            {{-- SECCIÓN DE INVITACIONES (Líder) --}}
                            @php
                                $participante = Auth::user()->participante;
                                $lider = $equipo->getLider();
                                $es_lider = $lider && $lider->id === $participante->id;
                            @endphp

                            @if($es_lider)
                                <div class="pt-8 border-t border-gray-100 dark:border-gray-700 mt-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Invitaciones Enviadas</h3>
                                    
                                    <a href="{{ route('participante.invitaciones.enviadas', $equipo) }}" 
                                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/30 transition-all">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Ver Historial
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script Alpine con Modal de Invitación --}}
    <script>
        function teamManager(participantsData) {
            return {
                search: '',
                participants: participantsData,
                selectedId: null,
                selectedName: '',
                showInviteModal: false,
                inviteMessage: '',
                suggestedRole: '',

                get filteredParticipants() {
                    if (this.search === '') return [];
                    const query = this.search.toLowerCase();
                    return this.participants.filter(p => p.name.toLowerCase().includes(query));
                },
                selectParticipant(p) {
                    this.selectedId = p.id;
                    this.selectedName = p.name;
                    this.search = '';
                },
                resetSelection() {
                    this.selectedId = null;
                    this.selectedName = '';
                    this.inviteMessage = '';
                    this.suggestedRole = '';
                },
                openInviteModal() {
                    this.showInviteModal = true;
                },
                closeInviteModal() {
                    this.showInviteModal = false;
                    this.inviteMessage = '';
                    this.suggestedRole = '';
                },
                sendInvitation() {
                    const form = document.getElementById('inviteForm');
                    if (form) {
                        form.submit();
                    }
                }
            }
        }
    </script>
</x-app-layout>