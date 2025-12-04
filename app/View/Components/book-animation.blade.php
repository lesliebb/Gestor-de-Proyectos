<div x-data="{ isOpen: false }" x-on:click="isOpen = !isOpen"
    class="relative w-full min-h-screen overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4">
    <!-- Fondo decorativo -->
    <div class="absolute inset-0 overflow-hidden">
        <div
            class="absolute -top-40 -right-40 w-80 h-80 bg-blue-300 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob">
        </div>
        <div
            class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000">
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="relative z-10 max-w-7xl mx-auto">
        <!-- Título -->
        <div class="text-center mb-16">
            <h1 class="text-5xl md:text-7xl font-bold text-gray-800 dark:text-white mb-6">
                Sistema de Gestión Académica
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                Descubre el futuro de la gestión de proyectos académicos a través de esta experiencia interactiva
            </p>
        </div>

        <!-- Contenedor del libro -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-12">
            <!-- Libro -->
            <div x-ref="bookContainer" class="relative w-full max-w-4xl h-[600px] perspective-1000 cursor-pointer">
                <!-- Cubierta del libro -->
                <div x-ref="bookCover"
                    class="absolute w-1/2 h-full bg-gradient-to-r from-blue-800 to-indigo-900 dark:from-gray-800 dark:to-gray-900 rounded-l-xl shadow-2xl border-r-8 border-amber-600 z-20 transition-transform duration-1000 origin-right preserve-3d">
                    <!-- Diseño de portada -->
                    <div class="absolute inset-0 p-8">
                        <div class="h-full border-4 border-amber-500/30 rounded-lg p-6 flex flex-col justify-between">
                            <div>
                                <h2 class="text-3xl font-bold text-white mb-4">Proyectos Académicos</h2>
                                <div class="w-20 h-1 bg-amber-400 mb-6"></div>
                                <p class="text-blue-200 text-lg">Edición 2025</p>
                            </div>
                            <div class="text-right">
                                <p class="text-white text-2xl font-semibold">Sistema Integral</p>
                                <p class="text-blue-200">v0.9.0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Lomo del libro -->
                    <div
                        class="absolute -right-8 top-4 w-8 h-[calc(100%-2rem)] bg-gradient-to-b from-amber-800 to-amber-900 rounded-r-lg">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="transform -rotate-90 text-white font-bold tracking-widest whitespace-nowrap">
                                GESTIÓN ACADÉMICA
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Páginas del libro -->
                <div x-ref="bookPages"
                    class="absolute left-1/2 w-1/2 h-full bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-gray-800 dark:to-gray-700 rounded-r-xl shadow-2xl z-10 preserve-3d overflow-hidden">
                    <!-- Contenido de las páginas -->
                    <div class="h-full p-8 overflow-y-auto">
                        <!-- Página 1: Módulo Admin -->
                        <div class="page-content mb-12">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Módulo Administrador</h3>
                            </div>
                            <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Gestión completa de usuarios y roles
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Dashboard con métricas en tiempo real
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Generación automática de constancias PDF
                                </li>
                            </ul>
                        </div>

                        <!-- Página 2: Módulo Participante -->
                        <div class="page-content mb-12">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 3.75l-5.5 5.5m0 0l-5.5-5.5m5.5 5.5V3" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Módulo Participante</h3>
                            </div>
                            <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Gestión de equipos y proyectos
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Bitácora de avances integrada
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Descarga de diplomas automática
                                </li>
                            </ul>
                        </div>

                        <!-- Página 3: Módulo Juez -->
                        <div class="page-content">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Módulo Juez</h3>
                            </div>
                            <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Sistema de evaluación ponderada
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Sliders interactivos de calificación
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Feedback en tiempo real
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Separador de páginas -->
                    <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-amber-200 to-amber-400"></div>
                </div>
            </div>

            <!-- Panel de controles -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 w-full max-w-md">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Controles del Libro</h3>

                <div class="space-y-6">
                    <button @click="isOpen = true" x-show="!isOpen"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Abrir Libro
                    </button>

                    <button @click="isOpen = false" x-show="isOpen"
                        class="w-full bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold py-3 px-6 rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Cerrar Libro
                    </button>

                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Características</h4>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Animaciones fluidas con GSAP
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Modo oscuro integrado
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Diseño completamente responsivo
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instrucción -->
        <div class="text-center mt-12">
            <p class="text-gray-600 dark:text-gray-400 animate-pulse">
                Haz clic en el libro para abrirlo o usa los controles
            </p>
        </div>
    </div>
</div>

<!-- Estilos adicionales -->
<style>
    .perspective-1000 {
        perspective: 1000px;
    }

    .preserve-3d {
        transform-style: preserve-3d;
    }

    .page-content {
        transform: translateZ(30px);
    }

    @keyframes blob {
        0% {
            transform: translate(0px, 0px) scale(1);
        }

        33% {
            transform: translate(30px, -50px) scale(1.1);
        }

        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }

        100% {
            transform: translate(0px, 0px) scale(1);
        }
    }

    .animate-blob {
        animation: blob 7s infinite;
    }

    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>

<!-- Script GSAP -->
@push('scripts')
    <script type="module">
        import { gsap } from 'gsap';

        document.addEventListener('alpine:init', () => {
            Alpine.data('bookAnimation', () => ({
                isOpen: false,

                init() {
                    // Inicializar animación
                    this.setupAnimations();

                    // Escuchar cambios en isOpen
                    this.$watch('isOpen', (value) => {
                        if (value) {
                            this.openBook();
                        } else {
                            this.closeBook();
                        }
                    });

                    // Abrir automáticamente después de 1 segundo
                    setTimeout(() => {
                        this.isOpen = true;
                    }, 1000);
                },

                setupAnimations() {
                    // Configuración inicial
                    gsap.set(this.$refs.bookCover, {
                        rotationY: 0,
                        transformOrigin: "right center"
                    });

                    gsap.set(this.$refs.bookPages, {
                        rotationY: 0,
                        x: 0
                    });
                },

                openBook() {
                    // Animación de apertura
                    const timeline = gsap.timeline({
                        defaults: {
                            duration: 1.5,
                            ease: "power3.out"
                        }
                    });

                    timeline
                        // Mover el libro al centro
                        .to(this.$refs.bookContainer, {
                            x: -100,
                            duration: 1,
                            ease: "back.out(1.7)"
                        })
                        // Abrir la portada
                        .to(this.$refs.bookCover, {
                            rotationY: -170,
                            duration: 1.8,
                            ease: "power3.inOut"
                        }, 0.2)
                        // Revelar páginas con efecto de profundidad
                        .to(this.$refs.bookPages, {
                            rotationY: -10,
                            duration: 1.5,
                            ease: "power2.out"
                        }, 0.5)
                        // Efecto de iluminación en la portada
                        .to(this.$refs.bookCover, {
                            boxShadow: "0 0 40px rgba(59, 130, 246, 0.5)",
                            duration: 0.5
                        }, 0.2)
                        // Animación de contenido de páginas
                        .from('.page-content', {
                            opacity: 0,
                            y: 30,
                            stagger: 0.2,
                            duration: 1,
                            ease: "power2.out"
                        }, 1);
                },

                closeBook() {
                    // Animación de cierre
                    const timeline = gsap.timeline({
                        defaults: {
                            duration: 1.2,
                            ease: "power3.inOut"
                        }
                    });

                    timeline
                        // Cerrar la portada
                        .to(this.$refs.bookCover, {
                            rotationY: 0,
                            duration: 1.5,
                            ease: "power3.inOut"
                        })
                        // Restaurar posición de páginas
                        .to(this.$refs.bookPages, {
                            rotationY: 0,
                            duration: 1.2
                        }, 0)
                        // Restaurar posición del libro
                        .to(this.$refs.bookContainer, {
                            x: 0,
                            duration: 1,
                            ease: "power2.out"
                        }, 0.5)
                        // Quitar efecto de iluminación
                        .to(this.$refs.bookCover, {
                            boxShadow: "none",
                            duration: 0.3
                        }, 0);
                }
            }));
        });
    </script>
@endpush