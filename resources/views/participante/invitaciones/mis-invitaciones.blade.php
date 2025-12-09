<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mis Invitaciones') }}
            </h2>
            <a href="{{ route('participante.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 text-green-700 dark:text-green-300 text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($invitaciones->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">No tienes invitaciones</h3>
                    <p class="text-gray-500 dark:text-gray-400">Los líderes de equipos pueden invitarte a unirte a sus proyectos</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($invitaciones as $inv)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md dark:hover:shadow-gray-700/50 transition-shadow">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $inv->equipo->nombre }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            Invitación enviada por <span class="font-semibold">{{ $inv->enviadaPor->user->name }}</span>
                                        </p>
                                        @if($inv->perfilSugerido)
                                            <div class="mt-3 flex items-center gap-2">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                    Rol sugerido: {{ $inv->perfilSugerido->nombre }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- Badge de Estado --}}
                                    <div>
                                        @if($inv->estado === 'pendiente')
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800 whitespace-nowrap">
                                                Pendiente
                                            </span>
                                        @elseif($inv->estado === 'aceptada')
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400 border border-green-200 dark:border-green-800 whitespace-nowrap">
                                                Aceptada
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-sm font-bold bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-400 border border-red-200 dark:border-red-800 whitespace-nowrap">
                                                Rechazada
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Mensaje de la Invitación --}}
                                @if($inv->mensaje)
                                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 italic">
                                            {{ $inv->mensaje }}
                                        </p>
                                    </div>
                                @endif

                                {{-- Botones de Acción (Solo si está pendiente) --}}
                                @if($inv->estado === 'pendiente')
                                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex gap-3">
                                        <form action="{{ route('participante.invitaciones.aceptar', $inv) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-lg transition-colors flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Aceptar
                                            </button>
                                        </form>
                                        <form action="{{ route('participante.invitaciones.rechazar', $inv) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-lg transition-colors flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                Rechazar
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Respondida el {{ $inv->respondida_en->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- Paginación --}}
                    <div class="mt-6">
                        {{ $invitaciones->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
