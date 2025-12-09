<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Invitar a Participante
            </h2>
            <a href="{{ route('participante.equipos.edit', $equipo) }}" 
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 text-green-700 dark:text-green-300 text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-700 dark:text-red-300 text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $equipo->nombre }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Selecciona a un participante sin equipo para invitarlo</p>
                </div>

                <form action="{{ route('participante.invitaciones.enviar', $equipo) }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Seleccionar participante --}}
                    <div>
                        <label for="participante_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Participante
                        </label>
                        @if($participantesSinEquipo->isEmpty())
                            <div class="p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-300 text-sm">
                                No hay participantes sin equipo disponibles
                            </div>
                        @else
                            <select id="participante_id" name="participante_id" required 
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                <option value="">-- Selecciona un participante --</option>
                                @foreach($participantesSinEquipo as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->user->name }} ({{ $p->no_control }}) - {{ $p->carrera->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('participante_id')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    {{-- Rol sugerido --}}
                    <div>
                        <label for="perfil_sugerido_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Rol Sugerido (opcional)
                        </label>
                        <select id="perfil_sugerido_id" name="perfil_sugerido_id" 
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            <option value="">-- Sin especificar --</option>
                            @foreach($rolesDisponibles as $rol)
                                <option value="{{ $rol['id'] }}">
                                    {{ $rol['nombre'] }} ({{ $rol['disponibles'] }}/{{ $rol['total'] }} vacantes)
                                </option>
                            @endforeach
                        </select>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">El participante podrá aceptar o rechazar el rol sugerido</p>
                        @error('perfil_sugerido_id')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mensaje --}}
                    <div>
                        <label for="mensaje" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Mensaje Personal (opcional)
                        </label>
                        <textarea id="mensaje" name="mensaje" rows="4" 
                                  placeholder="Cuéntale al participante por qué lo invitas..."
                                  class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"
                                  maxlength="500"></textarea>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Máximo 500 caracteres</p>
                        @error('mensaje')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botones --}}
                    <div class="flex gap-4 pt-4">
                        <button type="submit" 
                                @if($participantesSinEquipo->isEmpty()) disabled @endif
                                class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/30 transition-all">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Enviar Invitación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
