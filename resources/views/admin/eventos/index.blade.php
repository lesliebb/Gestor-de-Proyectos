<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Eventos') }}
            </h2>
            <a href="{{ route('admin.eventos.create') }}"
                class="bg-indigo-600 dark:bg-indigo-500 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-500 dark:hover:bg-indigo-400">
                + Nuevo Evento
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 dark:bg-green-900 dark:border-green-600 dark:text-green-300 p-4 mb-4"
                    role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 relative mb-8">

                <!-- View Mode Controls -->
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-4">
                        <button id="prevBtn"
                            class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <h3 id="currentLabel"
                            class="text-2xl font-bold text-gray-800 dark:text-gray-100 capitalize cursor-pointer hover:text-indigo-600 transition"
                            title="Cambiar vista"></h3>
                        <button id="nextBtn"
                            class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div>
                        <button id="toggleViewBtn"
                            class="text-sm bg-indigo-50 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 px-3 py-1 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                            Ver Año
                        </button>
                    </div>
                </div>

                <!-- Month View Grid (Default) -->
                <div id="monthView">
                    <div class="grid grid-cols-7 gap-4 mb-4">
                        <div class="text-center font-bold text-gray-500 dark:text-gray-400 uppercase text-sm">Dom</div>
                        <div class="text-center font-bold text-gray-500 dark:text-gray-400 uppercase text-sm">Lun</div>
                        <div class="text-center font-bold text-gray-500 dark:text-gray-400 uppercase text-sm">Mar</div>
                        <div class="text-center font-bold text-gray-500 dark:text-gray-400 uppercase text-sm">Mié</div>
                        <div class="text-center font-bold text-gray-500 dark:text-gray-400 uppercase text-sm">Jue</div>
                        <div class="text-center font-bold text-gray-500 dark:text-gray-400 uppercase text-sm">Vie</div>
                        <div class="text-center font-bold text-gray-500 dark:text-gray-400 uppercase text-sm">Sáb</div>
                    </div>
                    <div id="calendarGrid" class="grid grid-cols-7 gap-4">
                        <!-- Days will be injected here -->
                    </div>
                </div>

                <!-- Year View Grid (Hidden by default) -->
                <div id="yearView" class="hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <!-- Mini Calendars will be injected here -->
                </div>

            </div>

            <!-- Restore Table View -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fechas</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($eventos as $evento)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $evento->nombre }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 truncate w-48">
                                            {{ $evento->descripcion }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <div>Inicio: {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}
                                        </div>
                                        <div>Fin: {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(\Carbon\Carbon::now()->between($evento->fecha_inicio, $evento->fecha_fin))
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">En
                                                curso</span>
                                        @elseif(\Carbon\Carbon::now()->lt($evento->fecha_inicio))
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">Próximo</span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">Finalizado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.eventos.show', $evento) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Ver</a>
                                        <a href="{{ route('admin.eventos.edit', $evento) }}"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Editar</a>
                                        <form action="{{ route('admin.eventos.destroy', $evento) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-white dark:bg-gray-800">
                    {{ $eventos->links() }}
                </div>
            </div>

            <!-- Styles for Marquee -->
            <style>
                @keyframes marquee {
                    0% {
                        transform: translateX(0);
                    }

                    100% {
                        transform: translateX(-100%);
                    }
                }

                .animate-marquee {
                    display: inline-block;
                    padding-left: 100%;
                    animation: marquee 5s linear infinite;
                }

                .group:hover .group-hover\:animate-marquee {
                    animation: marquee 5s linear infinite;
                }
            </style>

            <!-- Event Data Injection -->
            <script>
                const events = @json($eventos->items());
                
                // Check for date parameter from URL to open calendar in specific month
                const urlParams = new URLSearchParams(window.location.search);
                const dateParam = urlParams.get('date');
                let currentDate = dateParam ? new Date(dateParam) : new Date();
                let viewMode = 'month'; // 'month' or 'year'
                let tooltipTimeout;

                const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

                function render() {
                    const currentLabel = document.getElementById('currentLabel');
                    const toggleBtn = document.getElementById('toggleViewBtn');
                    const monthView = document.getElementById('monthView');
                    const yearView = document.getElementById('yearView');

                    if (viewMode === 'month') {
                        monthView.classList.remove('hidden');
                        yearView.classList.add('hidden');
                        toggleBtn.textContent = 'Ver Año';
                        currentLabel.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
                        renderMonthGrid();
                    } else {
                        monthView.classList.add('hidden');
                        yearView.classList.remove('hidden');
                        toggleBtn.textContent = 'Ver Mes';
                        currentLabel.textContent = `${currentDate.getFullYear()}`;
                        renderYearGrid();
                    }
                }

                function renderMonthGrid() {
                    const grid = document.getElementById('calendarGrid');
                    grid.innerHTML = '';

                    const year = currentDate.getFullYear();
                    const month = currentDate.getMonth();
                    const firstDay = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();

                    // Empty cells
                    for (let i = 0; i < firstDay; i++) {
                        const cell = document.createElement('div');
                        cell.classList.add('h-32', 'bg-gray-50', 'dark:bg-gray-900/50', 'rounded-lg');
                        grid.appendChild(cell);
                    }

                    // Days
                    for (let day = 1; day <= daysInMonth; day++) {
                        const cell = document.createElement('div');
                        cell.classList.add('h-32', 'bg-white', 'dark:bg-gray-700', 'border', 'border-gray-200', 'dark:border-gray-600', 'rounded-lg', 'p-2', 'relative', 'hover:shadow-md', 'transition', 'overflow-hidden');

                        const dateNum = document.createElement('div');
                        dateNum.textContent = day;
                        dateNum.classList.add('font-bold', 'text-gray-700', 'dark:text-gray-300', 'mb-1');
                        if (day === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) {
                            dateNum.classList.add('text-indigo-600', 'dark:text-indigo-400');
                        }
                        cell.appendChild(dateNum);

                        // Events
                        const dayEvents = events.filter(e => {
                            const eDate = new Date(e.fecha_inicio);
                            return eDate.getDate() === day && eDate.getMonth() === month && eDate.getFullYear() === year;
                        });

                        dayEvents.forEach(event => {
                            const eventEl = document.createElement('div');
                            // Marquee Structure
                            eventEl.innerHTML = `<span class="whitespace-nowrap group-hover:animate-marquee inline-block">${event.nombre}</span>`;

                            eventEl.classList.add('group', 'text-xs', 'bg-indigo-100', 'dark:bg-indigo-900', 'text-indigo-700', 'dark:text-indigo-200', 'rounded', 'px-1', 'py-0.5', 'mb-1', 'overflow-hidden', 'cursor-pointer', 'relative');

                            // Click to Edit
                            eventEl.addEventListener('click', (e) => {
                                e.stopPropagation(); // Prevent bubbling if needed
                                window.location.href = `/admin/eventos/${event.id}/edit`;
                            });

                            // Persistent Tooltip Logic
                            eventEl.addEventListener('mouseenter', (e) => {
                                clearTimeout(tooltipTimeout);
                                showTooltip(e, event);
                            });
                            eventEl.addEventListener('mouseleave', () => {
                                tooltipTimeout = setTimeout(hideTooltip, 300); // 300ms delay
                            });

                            cell.appendChild(eventEl);
                        });

                        grid.appendChild(cell);
                    }
                }

                function renderYearGrid() {
                    const grid = document.getElementById('yearView');
                    grid.innerHTML = '';

                    monthNames.forEach((name, index) => {
                        const monthContainer = document.createElement('div');
                        monthContainer.classList.add('bg-white', 'dark:bg-gray-700', 'border', 'border-gray-200', 'dark:border-gray-600', 'rounded-lg', 'p-3');

                        // Month Title
                        const title = document.createElement('h4');
                        title.textContent = name;
                        title.classList.add('text-center', 'font-bold', 'text-gray-800', 'dark:text-gray-100', 'mb-2', 'cursor-pointer', 'hover:text-indigo-500');
                        title.addEventListener('click', () => {
                            currentDate.setMonth(index);
                            viewMode = 'month';
                            render();
                        });
                        monthContainer.appendChild(title);

                        // Mini Grid
                        const miniGrid = document.createElement('div');
                        miniGrid.classList.add('grid', 'grid-cols-7', 'gap-1', 'text-xs');

                        const year = currentDate.getFullYear();
                        const firstDay = new Date(year, index, 1).getDay();
                        const daysInMonth = new Date(year, index + 1, 0).getDate();

                        // Empty cells
                        for (let i = 0; i < firstDay; i++) {
                            miniGrid.appendChild(document.createElement('div'));
                        }

                        // Days
                        for (let day = 1; day <= daysInMonth; day++) {
                            const dayCell = document.createElement('div');
                            dayCell.textContent = day;
                            dayCell.classList.add('text-center', 'p-1', 'rounded-full', 'cursor-pointer', 'hover:bg-gray-100', 'dark:hover:bg-gray-600', 'text-gray-600', 'dark:text-gray-300');

                            // Check for events
                            const dayEvents = events.filter(e => {
                                const eDate = new Date(e.fecha_inicio);
                                return eDate.getDate() === day && eDate.getMonth() === index && eDate.getFullYear() === year;
                            });

                            if (dayEvents.length > 0) {
                                dayCell.classList.add('bg-indigo-100', 'dark:bg-indigo-900', 'text-indigo-700', 'dark:text-indigo-200', 'font-bold');

                                // Tooltip on Hover/Click
                                dayCell.addEventListener('mouseenter', (e) => {
                                    clearTimeout(tooltipTimeout);
                                    showTooltip(e, dayEvents[0]); // Show first event
                                });
                                dayCell.addEventListener('mouseleave', () => {
                                    tooltipTimeout = setTimeout(hideTooltip, 300);
                                });
                                dayCell.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    showTooltip(e, dayEvents[0]);
                                });
                                // Double Click to Edit
                                dayCell.addEventListener('dblclick', (e) => {
                                    e.stopPropagation();
                                    window.location.href = `/admin/eventos/${dayEvents[0].id}/edit`;
                                });
                            }

                            miniGrid.appendChild(dayCell);
                        }

                        monthContainer.appendChild(miniGrid);
                        grid.appendChild(monthContainer);
                    });
                }

                // Tooltip
                const tooltip = document.createElement('div');
                tooltip.classList.add('fixed', 'hidden', 'z-50', 'w-64', 'bg-white', 'dark:bg-gray-800', 'rounded-lg', 'shadow-xl', 'p-4', 'border', 'border-gray-200', 'dark:border-gray-700', 'text-sm');
                // Keep tooltip open when hovering over it
                tooltip.addEventListener('mouseenter', () => clearTimeout(tooltipTimeout));
                tooltip.addEventListener('mouseleave', () => hideTooltip());
                document.body.appendChild(tooltip);

                function showTooltip(e, event) {
                    const rect = e.target.getBoundingClientRect();
                    tooltip.style.left = `${rect.left}px`;
                    tooltip.style.top = `${rect.bottom + 5}px`;
                    tooltip.classList.remove('hidden');

                    const now = new Date();
                    const start = new Date(event.fecha_inicio);
                    const end = new Date(event.fecha_fin);
                    let statusHtml = '';
                    if (now >= start && now <= end) {
                        statusHtml = '<span class="text-green-600 font-bold">En curso</span>';
                    } else if (now < start) {
                        statusHtml = '<span class="text-blue-600 font-bold">Próximo</span>';
                    } else {
                        statusHtml = '<span class="text-gray-500 font-bold">Finalizado</span>';
                    }

                    const showRoute = `/admin/eventos/${event.id}`;
                    const editRoute = `/admin/eventos/${event.id}/edit`;
                    const deleteRoute = `/admin/eventos/${event.id}`;

                    tooltip.innerHTML = `
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">${event.nombre}</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mb-2">${event.descripcion || ''}</p>
                        <div class="mb-2 text-xs text-gray-600 dark:text-gray-300">
                            <div>Inicio: ${start.toLocaleDateString()}</div>
                            <div>Fin: ${end.toLocaleDateString()}</div>
                            <div class="mt-1">${statusHtml}</div>
                        </div>
                        <div class="flex justify-between mt-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <a href="${showRoute}" class="text-blue-600 hover:underline text-xs">Ver</a>
                            <a href="${editRoute}" class="text-indigo-600 hover:underline text-xs">Editar</a>
                            <button onclick="deleteEvent('${deleteRoute}')" class="text-red-600 hover:underline text-xs">Eliminar</button>
                        </div>
                    `;
                }

                function hideTooltip() {
                    tooltip.classList.add('hidden');
                }

                // Delete Helper
                window.deleteEvent = function (route) {
                    if (confirm('¿Estás seguro de eliminar este evento?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = route;
                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                        form.appendChild(csrf);
                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';
                        form.appendChild(method);
                        document.body.appendChild(form);
                        form.submit();
                    }
                }

                // Navigation
                document.getElementById('prevBtn').addEventListener('click', () => {
                    if (viewMode === 'month') {
                        currentDate.setMonth(currentDate.getMonth() - 1);
                    } else {
                        currentDate.setFullYear(currentDate.getFullYear() - 1);
                    }
                    render();
                });

                document.getElementById('nextBtn').addEventListener('click', () => {
                    if (viewMode === 'month') {
                        currentDate.setMonth(currentDate.getMonth() + 1);
                    } else {
                        currentDate.setFullYear(currentDate.getFullYear() + 1);
                    }
                    render();
                });

                document.getElementById('toggleViewBtn').addEventListener('click', () => {
                    viewMode = viewMode === 'month' ? 'year' : 'month';
                    render();
                });

                document.getElementById('currentLabel').addEventListener('click', () => {
                    if (viewMode === 'month') {
                        viewMode = 'year';
                        render();
                    }
                });

                // Initial Render
                render();
            </script>
        </div>
    </div>
</x-app-layout>