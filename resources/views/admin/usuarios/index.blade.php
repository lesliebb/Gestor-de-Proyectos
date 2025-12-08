<x-app-layout>
    {{-- Inicializamos Alpine para controlar el modal desde cualquier parte de esta vista --}}
    <div class="py-12" x-data="{ showDeleteModal: false, deleteAction: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Encabezado --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Gestión de Usuarios</h2>
                
                <div class="flex gap-3">
                    {{-- Botón Exportar a Excel --}}
                    <a href="{{ route('admin.usuarios.exportar', request()->all()) }}" 
                       class="inline-flex items-center justify-center rounded-md bg-green-600 py-2 px-6 text-center font-medium text-white hover:bg-opacity-90 lg:px-8 xl:px-10 gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Exportar a Excel
                    </a>
                    
                    <a href="{{ route('admin.usuarios.create') }}" 
                       class="inline-flex items-center justify-center rounded-md bg-indigo-600 py-2 px-6 text-center font-medium text-white hover:bg-opacity-90 lg:px-8 xl:px-10 gap-2">
                        <span>+</span> Nuevo Usuario
                    </a>
                </div>
            </div>

            {{-- Alertas --}}
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Contenedor Tabla --}}
            <div class="rounded-sm border border-gray-200 bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-gray-700 dark:bg-gray-800 sm:px-7.5 xl:pb-1 sm: rounded-3xl">
                
                {{-- Filtros --}}
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <form action="{{ route('admin.usuarios.index') }}" method="GET" class="flex flex-grow flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar usuario..." 
                                class="w-full rounded border-[1.5px] border-gray-300 bg-transparent py-2 pl-10 pr-4 font-medium outline-none transition focus:border-indigo-600 active:border-indigo-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-indigo-600">
                        </div>

                        <div class="relative w-full sm:w-48">
                            <select name="role" onchange="this.form.submit()"
                                class="w-full appearance-none rounded border-[1.5px] border-gray-300 bg-transparent py-2 px-4 font-medium outline-none transition focus:border-indigo-600 active:border-indigo-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-indigo-600">
                                <option value="">Todos los roles</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->nombre }}" {{ request('role') == $rol->nombre ? 'selected' : '' }}>{{ $rol->nombre }}</option>
                                @endforeach
                            </select>
                            <span class="absolute top-1/2 right-4 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </span>
                        </div>
                    </form>

                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Total: <span class="text-indigo-600 dark:text-indigo-400">{{ $usuarios->total() }}</span>
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="max-w-full overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                <th class="min-w-[220px] py-4 px-4 font-medium text-black dark:text-white xl:pl-11">Usuario</th>
                                <th class="min-w-[150px] py-4 px-4 font-medium text-black dark:text-white">Rol</th>
                                <th class="min-w-[120px] py-4 px-4 font-medium text-black dark:text-white">Fecha Registro</th>
                                <th class="py-4 px-4 font-medium text-black dark:text-white text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                                <tr class="border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                    <td class="py-5 px-4 pl-9 xl:pl-11">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                            <div class="h-10 w-10 rounded-full bg-indigo-50 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-lg overflow-hidden shrink-0">
                                                @php
                                                    $avatarPath = 'storage/avatars/' . $usuario->id . '.jpg';
                                                    $hasAvatar = file_exists(public_path($avatarPath));
                                                @endphp
                                                @if($hasAvatar)
                                                    <img src="{{ asset($avatarPath) . '?v=' . time() }}" alt="Avatar" class="h-full w-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($usuario->nombre ?: $usuario->email, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div>
                                                <h5 class="font-medium text-black dark:text-white">{{ $usuario->nombre }}</h5>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 px-4">
                                        @foreach($usuario->roles as $rol)
                                            @php
                                                $badgeClass = match($rol->nombre) {
                                                    'Admin' => 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400',
                                                    'Juez' => 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400',
                                                    'Participante' => 'bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400',
                                                    default => 'bg-gray-100 text-gray-600 dark:bg-gray-500/20 dark:text-gray-400',
                                                };
                                            @endphp
                                            <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $badgeClass }}">
                                                {{ $rol->nombre }}
                                            </span>
                                        @endforeach
                                    </td>
                                    
                                    <td class="py-5 px-4">
                                        <p class="text-black dark:text-white">{{ $usuario->created_at->format('d M, Y') }}</p>
                                    </td>
                                    <td class="py-5 px-4 text-right">
                                        <div class="flex items-center justify-end space-x-3.5">
                                            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 text-gray-500 transition">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </a>

                                            @if(auth()->id() !== $usuario->id)
                                                {{-- BOTÓN ELIMINAR QUE ACTIVA EL MODAL --}}
                                                <button @click="showDeleteModal = true; deleteAction = '{{ route('admin.usuarios.destroy', $usuario) }}'" 
                                                        class="hover:text-red-600 dark:hover:text-red-400 text-gray-500 transition">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="py-6 px-4">
                    {{ $usuarios->links('components.pagination') }}
                </div>
            </div>
        </div>

        {{-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN --}}
        <div x-show="showDeleteModal" 
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800 text-center"
                 @click.away="showDeleteModal = false">
                
                {{-- Icono de Alerta --}}
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">¿Estás seguro?</h3>
                <p class="mb-6 text-gray-500 dark:text-gray-400">
                    Estás a punto de eliminar este usuario. Esta acción es irreversible y podría afectar los equipos asociados.
                </p>

                <div class="flex justify-center gap-4">
                    <button @click="showDeleteModal = false" 
                            class="rounded-md border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    
                    {{-- Formulario Dinámico --}}
                    <form :action="deleteAction" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="rounded-md bg-red-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                            Sí, eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>