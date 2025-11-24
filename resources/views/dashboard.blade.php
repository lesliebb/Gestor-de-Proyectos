<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-8 text-white">
                    <h3 class="text-3xl font-extrabold mb-2">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                    <p class="text-indigo-100 text-lg">Has iniciado sesión correctamente. Este es tu panel de control
                        principal.</p>
                </div>
            </div>

            <!-- Quick Stats (Placeholder) -->
            <div class="grid grid-cols-1 gap-6">
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center">
                        <div
                            class="p-4 rounded-full bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <div
                                class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">
                                Perfil</div>
                            <div class="text-xl font-bold text-gray-800 dark:text-gray-100">Activo</div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center">
                        <div
                            class="p-4 rounded-full bg-green-50 dark:bg-green-900/50 text-green-600 dark:text-green-400 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div
                                class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">
                                Estado</div>
                            <div class="text-xl font-bold text-gray-800 dark:text-gray-100">Verificado</div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center">
                        <div
                            class="p-4 rounded-full bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div
                                class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">
                                Sesión</div>
                            <div class="text-xl font-bold text-gray-800 dark:text-gray-100">En línea</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>