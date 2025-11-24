<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Equipo') }}: <span class="text-indigo-600 dark:text-indigo-400">{{ $equipo->nombre }}</span>
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.equipos.edit', $equipo) }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-md text-sm hover:bg-gray-300 dark:hover:bg-gray-600">Renombrar</a>
                <a href="{{ route('admin.equipos.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm underline">Volver</a>
            </div>
        </div>
    </x-slot>

    {{-- Preparar datos para AlpineJS --}}
    @php
        // Transformamos la data a un formato simple para JS
        $participantsData = $todos_participantes->map(function($p) use ($equipo) {
            $equipoActual = $p->equipos->first(); // Asumimos un equipo activo a la vez
            return [
                'id' => $p->id,
                'name' => $p->user->name,
                'no_control' => $p->no_control ?? 'S/N',
                'carrera' => $p->carrera->nombre ?? 'N/A',
                'has_team' => $p->equipos->isNotEmpty(),
                'team_name' => $equipoActual ? $equipoActual->nombre : '',
                'in_this_team' => $p->equipos->contains($equipo->id),
            ];
        });
    @endphp

    <div class="py-12" x-data="teamManager({{ $participantsData->toJson() }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 dark:bg-green-900 dark:border-green-600 dark:text-green-300 p-4 mb-6 rounded shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 dark:bg-red-900 dark:border-red-600 dark:text-red-300 p-4 mb-6 rounded shadow-sm">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- COLUMNA IZQUIERDA: Buscador Inteligente --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-2 border-indigo-50 dark:border-gray-700 h-full">
                        <h3 class="text-md font-bold text-indigo-900 dark:text-indigo-300 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Agregar Miembro
                        </h3>

                        {{-- 1. Formulario de Confirmación (Aparece al seleccionar) --}}
                        <div x-show="selectedId" x-transition class="mb-4 bg-indigo-50 dark:bg-gray-900 p-4 rounded border border-indigo-200 dark:border-gray-700 relative">
                            <button @click="resetSelection()" class="absolute top-2 right-2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">✕</button>
                            
                            <p class="text-xs text-indigo-800 dark:text-indigo-300 uppercase font-bold mb-1">Seleccionado:</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-2" x-text="selectedName"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">No. Control: <span x-text="selectedControl"></span></p>

                            <form action="{{ route('admin.equipos.miembros.store', $equipo) }}" method="POST">
                                @csrf
                                <input type="hidden" name="participante_id" x-model="selectedId">
                                
                                <div class="mb-3">
                                    <x-input-label for="perfil_id" value="Rol en el equipo" class="text-xs" />
                                    <select name="perfil_id" class="block w-full border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 rounded text-sm focus:ring-indigo-500 py-1" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($perfiles as $perfil)
                                            <option value="{{ $perfil->id }}">{{ $perfil->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full bg-indigo-600 dark:bg-indigo-500 text-white py-2 rounded text-sm hover:bg-indigo-500 dark:hover:bg-indigo-400 shadow">
                                    Confirmar Agregado
                                </button>
                            </form>
                        </div>

                        {{-- 2. Buscador --}}
                        <div class="mb-2 relative">
                            <label class="text-xs text-gray-500 dark:text-gray-400 font-bold mb-1 block">BUSCAR ALUMNO</label>
                            <input type="text" x-model="search" placeholder="Escribe No. Control o Nombre..." 
                                class="w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 pl-9">
                            <div class="absolute top-7 left-3 text-gray-400 dark:text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>

                        {{-- 3. Mensaje de Error (NO EXISTE) --}}
                        <div x-show="search.length > 0 && filteredParticipants.length === 0" class="p-4 bg-red-50 dark:bg-red-900/50 border border-red-100 dark:border-red-900 rounded-lg text-center mb-4">
                            <div class="text-red-500 dark:text-red-400 mb-1">
                                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-red-700 dark:text-red-300">No encontrado</p>
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">No existe ningún alumno con ese nombre o número de control.</p>
                        </div>

                        {{-- 4. Lista de Resultados --}}
                        <div class="max-h-96 overflow-y-auto border-t border-gray-100 dark:border-gray-700 mt-2" x-show="filteredParticipants.length > 0">
                            <p class="text-xs text-gray-400 dark:text-gray-500 py-2 text-center" x-show="!search">Mostrando todos los alumnos...</p>
                            
                            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                <template x-for="p in filteredParticipants" :key="p.id">
                                    <li class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer group" 
                                        :class="{'bg-gray-50 dark:bg-gray-700': p.id === selectedId, 'opacity-60 grayscale': p.in_this_team}"
                                        @click="!p.in_this_team && !p.has_team ? selectParticipant(p) : null">
                                        
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-sm font-bold text-gray-800 dark:text-gray-200" x-text="p.name"></div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono bg-gray-100 dark:bg-gray-600 inline-block px-1 rounded mt-1" x-text="p.no_control"></div>
                                                <div class="text-[10px] text-gray-400 dark:text-gray-500 uppercase mt-0.5" x-text="p.carrera"></div>
                                            </div>
                                            
                                            {{-- Etiquetas de Estado --}}
                                            <div>
                                                <template x-if="p.in_this_team">
                                                    <span class="px-2 py-1 rounded text-[10px] font-bold bg-gray-200 text-gray-600 border border-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500">
                                                        YA AGREGADO
                                                    </span>
                                                </template>
                                                
                                                <template x-if="!p.in_this_team && p.has_team">
                                                    <span class="px-2 py-1 rounded text-[10px] font-bold bg-red-100 text-red-600 border border-red-200 dark:bg-red-900/50 dark:text-red-400 dark:border-red-800" :title="'Está en: ' + p.team_name">
                                                        EN OTRO EQUIPO
                                                    </span>
                                                </template>

                                                <template x-if="!p.in_this_team && !p.has_team">
                                                    <span class="px-2 py-1 rounded text-[10px] font-bold bg-green-100 text-green-700 border border-green-200 group-hover:bg-green-200 dark:bg-green-900/50 dark:text-green-400 dark:border-green-800 dark:group-hover:bg-green-800/50">
                                                        DISPONIBLE
                                                    </span>
                                                </template>
                                            </div>
                                        </div>
                                        
                                        {{-- Feedback visual de equipo ocupado --}}
                                        <div x-show="!p.in_this_team && p.has_team" class="text-[10px] text-red-400 dark:text-red-500 mt-1 text-right">
                                            Pertenece a: <span class="font-bold" x-text="p.team_name"></span>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: Miembros y Proyecto --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Integrantes del Equipo</h3>
                            <span class="bg-white dark:bg-gray-700 border dark:border-gray-600 px-3 py-1 rounded-full text-xs font-bold text-gray-600 dark:text-gray-300">
                                {{ $equipo->participantes->count() }} Miembros
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-white dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Alumno</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Rol</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($equipo->participantes as $participante)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $participante->user->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $participante->no_control }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100 dark:bg-blue-900/50 dark:text-blue-300 dark:border-blue-800">
                                                {{ $participante->pivot->perfil->nombre ?? 'Sin asignar' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('admin.equipos.miembros.destroy', [$equipo, $participante]) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-xs font-bold uppercase" onclick="return confirm('¿Expulsar?')">Quitar</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">Sin miembros.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Proyecto Info --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-4 border-b dark:border-gray-700 pb-2">DATOS DEL PROYECTO</h3>
                        @if($equipo->proyecto)
                            <p class="text-lg font-bold text-indigo-700 dark:text-indigo-400 mb-1">{{ $equipo->proyecto->nombre }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">{{ $equipo->proyecto->descripcion }}</p>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="font-bold text-gray-500 dark:text-gray-400">EVENTO:</span>
                                <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $equipo->proyecto->evento->nombre ?? '---' }}</span>
                            </div>
                        @else
                            <p class="text-gray-400 dark:text-gray-500 italic text-sm">No hay proyecto registrado.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script de AlpineJS --}}
    <script>
        function teamManager(participantsData) {
            return {
                search: '',
                participants: participantsData,
                selectedId: null,
                selectedName: '',
                selectedControl: '',

                get filteredParticipants() {
                    if (this.search === '') {
                        return this.participants;
                    }
                    const query = this.search.toLowerCase();
                    return this.participants.filter(p => {
                        return p.name.toLowerCase().includes(query) || 
                               p.no_control.toLowerCase().includes(query);
                    });
                },

                selectParticipant(participant) {
                    this.selectedId = participant.id;
                    this.selectedName = participant.name;
                    this.selectedControl = participant.no_control;
                    this.search = ''; // Limpiar búsqueda al seleccionar
                },

                resetSelection() {
                    this.selectedId = null;
                    this.selectedName = '';
                    this.selectedControl = '';
                }
            }
        }
    </script>
</x-app-layout>