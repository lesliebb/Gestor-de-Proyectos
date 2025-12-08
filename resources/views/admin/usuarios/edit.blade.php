<x-app-layout>
    <div class="mx-auto max-w-270">

        {{-- Encabezado con Breadcrumb --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white text-2xl">
                Editar Usuario
            </h2>
            <nav>
                <ol class="flex items-center gap-2">
                    <li><a class="font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
                    <li><a class="font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600" href="{{ route('admin.usuarios.index') }}">Usuarios /</a></li>
                    <li class="font-medium text-indigo-600">Editar</li>
                </ol>
            </nav>
        </div>

        {{-- Contenedor Principal (Card) --}}
        <div class="rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-700 dark:bg-gray-800">
            
            <div class="border-b border-gray-200 py-4 px-6.5 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Actualizar Información
                </h3>
            </div>
            
            <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6.5">
                    
                    {{-- SECCIÓN 1: DATOS PERSONALES --}}
                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                        
                        {{-- Nombre --}}
                        <div class="w-full xl:w-1/2">
                            <label class="mb-2.5 block text-black dark:text-white font-medium">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="nombre" value="{{ old('nombre', $usuario->name) }}" placeholder="Ej. Juan Pérez" required
                                    class="w-full rounded border-[1.5px] bg-transparent py-3 px-5 font-medium outline-none transition disabled:cursor-default disabled:bg-whiter dark:bg-gray-700
                                    {{ $errors->has('nombre') 
                                        ? 'border-red-500 text-red-900 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:text-red-500' 
                                        : 'border-gray-300 text-black focus:border-indigo-600 dark:border-gray-600 dark:text-white dark:focus:border-indigo-600' 
                                    }}" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo letras y espacios" />
                                @error('nombre')
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </div>
                                @enderror
                            </div>
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2 text-red-500" />
                        </div>

                        {{-- Email --}}
                        <div class="w-full xl:w-1/2">
                            <label class="mb-2.5 block text-black dark:text-white font-medium">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" placeholder="usuario@correo.com" required
                                    class="w-full rounded border-[1.5px] bg-transparent py-3 px-5 font-medium outline-none transition disabled:cursor-default disabled:bg-whiter dark:bg-gray-700
                                    {{ $errors->has('email') 
                                        ? 'border-red-500 text-red-900 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:text-red-500' 
                                        : 'border-gray-300 text-black focus:border-indigo-600 dark:border-gray-600 dark:text-white dark:focus:border-indigo-600' 
                                    }}" />
                                @error('email')
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </div>
                                @enderror
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
                        </div>
                    </div>

                    {{-- Rol --}}
                    <div class="mb-4.5">
                        <label class="mb-2.5 block text-black dark:text-white font-medium">
                            Rol Asignado <span class="text-red-500">*</span>
                        </label>
                        <div class="relative z-20 bg-transparent dark:bg-gray-700 rounded">
                            <select name="rol_id" required
                                class="relative z-20 w-full appearance-none rounded border-[1.5px] bg-transparent py-3 px-5 outline-none transition disabled:cursor-default disabled:bg-whiter dark:bg-gray-700
                                {{ $errors->has('rol_id') 
                                    ? 'border-red-500 text-red-900 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:text-red-500' 
                                    : 'border-gray-300 text-black focus:border-indigo-600 dark:border-gray-600 dark:text-white dark:focus:border-indigo-600' 
                                }}">
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id }}" 
                                        {{ (old('rol_id') ?? $usuario->roles->first()->id ?? '') == $rol->id ? 'selected' : '' }}>
                                        {{ $rol->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute top-1/2 right-4 z-30 -translate-y-1/2">
                                <svg class="fill-current text-gray-500 dark:text-gray-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.29289 8.29289C5.68342 7.90237 6.31658 7.90237 6.70711 8.29289L12 13.5858L17.2929 8.29289C17.6834 7.90237 18.3166 7.90237 18.7071 8.29289C19.0976 8.68342 19.0976 9.31658 18.7071 9.70711L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L5.29289 9.70711C4.90237 9.31658 4.90237 8.68342 5.29289 8.29289Z"></path></svg>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('rol_id')" class="mt-2 text-red-500" />
                    </div>

                    {{-- SECCIÓN 2: SEGURIDAD --}}
                    <div class="mb-6 mt-8 border-b border-gray-200 pb-4 dark:border-gray-700">
                        <h4 class="font-medium text-gray-900 dark:text-white">Cambiar Contraseña (Opcional)</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Deja estos campos vacíos si no deseas cambiar la clave.</p>
                    </div>

                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                        {{-- Contraseña --}}
                        <div class="w-full xl:w-1/2">
                            <label class="mb-2.5 block text-black dark:text-white font-medium">Nueva Contraseña</label>
                            <div class="relative">
                                <input type="password" name="password" autocomplete="new-password" placeholder="Mínimo 8 caracteres"
                                    class="w-full rounded border-[1.5px] bg-transparent py-3 px-5 font-medium outline-none transition disabled:cursor-default disabled:bg-whiter dark:bg-gray-700
                                    {{ $errors->has('password') 
                                        ? 'border-red-500 text-red-900 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:text-red-500' 
                                        : 'border-gray-300 text-black focus:border-indigo-600 dark:border-gray-600 dark:text-white dark:focus:border-indigo-600' 
                                    }}" />
                                @error('password')
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </div>
                                @enderror
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
                        </div>

                        {{-- Confirmar Contraseña --}}
                        <div class="w-full xl:w-1/2">
                            <label class="mb-2.5 block text-black dark:text-white font-medium">Confirmar Nueva Contraseña</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" placeholder="Repite la contraseña"
                                    class="w-full rounded border-[1.5px] bg-transparent py-3 px-5 font-medium outline-none transition disabled:cursor-default disabled:bg-whiter dark:bg-gray-700
                                    {{ $errors->has('password_confirmation') 
                                        ? 'border-red-500 text-red-900 focus:border-red-500 focus:ring-red-500 dark:border-red-500 dark:text-red-500' 
                                        : 'border-gray-300 text-black focus:border-indigo-600 dark:border-gray-600 dark:text-white dark:focus:border-indigo-600' 
                                    }}" />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="flex justify-end gap-4.5 mt-8">
                        <a href="{{ route('admin.usuarios.index') }}"
                           class="flex justify-center rounded border border-gray-300 py-2 px-6 font-medium text-gray-700 hover:shadow-sm dark:border-gray-600 dark:text-gray-300 dark:hover:text-white transition">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="flex justify-center rounded bg-indigo-600 py-2 px-6 font-medium text-white hover:bg-opacity-90 hover:bg-indigo-700 transition">
                            Actualizar Usuario
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>