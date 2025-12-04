<header class="sticky top-0 z-40 flex w-full bg-white drop-shadow-sm dark:bg-gray-900 dark:drop-shadow-none border-b border-gray-200 dark:border-gray-800 transition-colors duration-300">
    <div class="flex flex-grow items-center justify-between px-4 py-3 shadow-2 md:px-6 2xl:px-11">

        <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
            <button @click.stop="sidebarOpen = !sidebarOpen"
                class="z-50 block rounded-sm border border-gray-200 bg-white p-1.5 shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:hidden hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <span class="block h-5.5 w-5.5 cursor-pointer">
                    <svg class="h-6 w-6 text-gray-600 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </span>
            </button>
        </div>

        <div class="hidden sm:block">
            </div>

        <div class="flex items-center gap-3 sm:gap-7">

            <button id="theme-toggle"
                class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-colors">
                <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>

            <div class="relative" x-data="{ dropdownOpen: false }">
                <a @click.prevent="dropdownOpen = !dropdownOpen" class="flex items-center gap-4 cursor-pointer select-none" href="#">
                    
                    <span class="hidden text-right lg:block">
                        <span class="block text-sm font-medium text-black dark:text-white">{{ Auth::user()->name }}</span>
                        <span class="block text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</span>
                    </span>

                    <span class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 overflow-hidden flex items-center justify-center">
                        <svg class="h-6 w-6 text-gray-400 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </span>

                    <svg :class="dropdownOpen && 'rotate-180'" class="hidden fill-current sm:block w-4 h-4 text-gray-500 transition-transform duration-200" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>

                <div x-show="dropdownOpen" 
                     @click.outside="dropdownOpen = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-4 flex w-56 flex-col rounded-md border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800 z-50"
                     style="display: none;">
                     
                    <ul class="flex flex-col border-b border-gray-200 dark:border-gray-700">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-6 py-4 text-sm font-medium duration-300 ease-in-out hover:text-indigo-600 hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300 dark:hover:text-white">
                                <svg class="fill-current w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                                Mi Perfil
                            </a>
                        </li>
                    </ul>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex w-full items-center gap-3.5 px-6 py-4 text-sm font-medium duration-300 ease-in-out hover:text-red-600 hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300 dark:hover:text-red-400 text-left">
                            <svg class="fill-current w-5 h-5" viewBox="0 0 24 24">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Lógica del Toggle Dark Mode
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        var themeToggleBtn = document.getElementById('theme-toggle');

        function updateIcons() {
            if (document.documentElement.classList.contains('dark')) {
                themeToggleDarkIcon.classList.add('hidden');
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
                themeToggleLightIcon.classList.add('hidden');
            }
        }

        // Inicialización
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        updateIcons();

        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('color-theme', 'dark');
            } else {
                localStorage.setItem('color-theme', 'light');
            }
            updateIcons();
        });
    </script>
</header>