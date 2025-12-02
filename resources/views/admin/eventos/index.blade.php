<x-app-layout>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Calendario de Eventos') }}
            </h2>
            {{-- Botón Agregar Evento Restaurado y Estilizado --}}
            <a href="{{ route('admin.eventos.create') }}"
                class="bg-indigo-600 dark:bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 dark:hover:bg-indigo-400 transition-all shadow-md hover:shadow-lg flex items-center gap-2 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Agregar evento
            </a>
        </div>

    <div class="py-8">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8 space-y-8"> {{-- Espaciado vertical entre secciones --}}

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 dark:bg-green-900/50 dark:border-green-500 dark:text-green-300 p-4 rounded-md shadow-sm backdrop-blur-sm"
                    role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- CONTENEDOR CALENDARIO (Estilo Dark Glass) --}}
            <div class="bg-white dark:bg-[#1a222c] border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xl sm:rounded-2xl p-6 relative">

                <!-- Calendar Header -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                    
                    {{-- Título (Ej: "2022" o "Enero 2022") --}}
                    <h3 id="currentLabel" class="text-3xl font-black text-gray-800 dark:text-white capitalize tracking-tight">
                        <!-- JS -->
                    </h3>

                    {{-- Controles Agrupados --}}
                    <div class="flex items-center gap-2 bg-gray-100 dark:bg-[#24303f] p-1 rounded-xl border border-gray-200 dark:border-gray-700">
                        
                        {{-- Botón Hoy --}}
                        <button id="todayBtn" class="px-4 py-1.5 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 rounded-lg shadow-sm transition-all">
                            Hoy
                        </button>

                        <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                        {{-- Navegación --}}
                        <button id="prevBtn" class="p-1.5 rounded-lg hover:bg-white dark:hover:bg-gray-600 text-gray-500 dark:text-gray-400 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button id="nextBtn" class="p-1.5 rounded-lg hover:bg-white dark:hover:bg-gray-600 text-gray-500 dark:text-gray-400 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>

                        <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>

                        {{-- Selector de Vista --}}
                        <select id="viewSelector" class="bg-transparent border-none text-sm font-bold text-gray-700 dark:text-white focus:ring-0 cursor-pointer py-1.5 pl-2 pr-8">
                            <option value="month" class="text-black">Vista Mensual</option>
                            <option value="year" class="text-black">Vista del año</option>
                        </select>
                    </div>
                </div>

                <!-- 1. VISTA MENSUAL (Month View) -->
                <div id="monthView" class="transition-opacity duration-300">
                    {{-- Encabezados de días --}}
                    <div class="grid grid-cols-7 mb-2">
                        @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $day)
                            <div class="text-center font-bold text-gray-400 dark:text-gray-500 uppercase text-xs tracking-widest py-2">{{ $day }}</div>
                        @endforeach
                    </div>
                    
                    {{-- Rejilla Mensual --}}
                    <div id="monthGrid" class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <!-- JS inyectará los días aquí -->
                    </div>
                </div>

                <!-- 2. VISTA ANUAL (Year View - Estilo Pro) -->
                <div id="yearView" class="hidden transition-opacity duration-300">
                    <div id="yearGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        <!-- JS inyectará los 12 meses aquí -->
                    </div>
                </div>

            </div>

            {{-- TABLA DE LISTA (Estilo Dark Glass alineado al Calendario) --}}
            <div class="bg-white dark:bg-[#1a222c] border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xl sm:rounded-2xl">
                
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Listado de Eventos</h3>
                    <a href="{{ route('admin.eventos.create') }}" 
                   class="inline-flex items-center justify-center rounded-md bg-indigo-600 py-2 px-6 text-center font-medium text-white hover:bg-opacity-90 lg:px-8 xl:px-10 gap-2">
                   <span>+</span> Agregar evento
                </a>
                </div>
                


                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm whitespace-nowrap">
                        <thead class="uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-500 dark:text-gray-400">
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-500 dark:text-gray-400">
                                    Fechas
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-500 dark:text-gray-400">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-500 dark:text-gray-400 text-right">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($eventos as $evento)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            {{-- Icono de calendario pequeño opcional --}}
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">{{ $evento->nombre }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]">{{ $evento->descripcion }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-gray-600 dark:text-gray-300 text-xs">
                                                <span class="font-bold text-indigo-500">INICIO:</span> {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}
                                            </span>
                                            <span class="text-gray-600 dark:text-gray-300 text-xs">
                                                <span class="font-bold text-pink-500">FIN:</span> &nbsp;&nbsp;&nbsp;&nbsp; {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(\Carbon\Carbon::now()->between($evento->fecha_inicio, $evento->fecha_fin))
                                            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400 border border-green-200 dark:border-green-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> En curso
                                            </span>
                                        @elseif(\Carbon\Carbon::now()->lt($evento->fecha_inicio))
                                            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Próximo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                                Finalizado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('admin.eventos.show', $evento) }}"
                                                class="p-2 rounded-lg text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors" title="Ver detalles">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('admin.eventos.edit', $evento) }}"
                                                class="p-2 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.eventos.destroy', $evento) }}" method="POST" class="inline-block">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                                                    onclick="return confirm('¿Estás seguro?')" title="Eliminar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $eventos->links('components.pagination') }}
                </div>
            </div>

        </div>
    </div>

    <style>
        /* Scrollbar oculta para limpieza visual */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        /* Truco para bordes compartidos en grid */
        #calendarGrid {
            border-collapse: collapse;
        }
    </style>

    <script>
        const events = @json($eventos->items());
        
        const urlParams = new URLSearchParams(window.location.search);
        const dateParam = urlParams.get('date');
        let currentDate = dateParam ? new Date(dateParam) : new Date();
        let viewMode = 'year'; // Empezamos en Year View como pediste ver el cambio
        let tooltipTimeout;

        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        const dayInitials = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];

        function render() {
            const currentLabel = document.getElementById('currentLabel');
            const viewSelector = document.getElementById('viewSelector');
            const monthView = document.getElementById('monthView');
            const yearView = document.getElementById('yearView');

            // Actualizar selector visual
            viewSelector.value = viewMode;

            if (viewMode === 'month') {
                monthView.classList.remove('hidden');
                yearView.classList.add('hidden');
                currentLabel.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
                renderMonthGrid();
            } else {
                monthView.classList.add('hidden');
                yearView.classList.remove('hidden');
                currentLabel.textContent = `${currentDate.getFullYear()}`;
                renderYearGrid();
            }
        }

        // --- RENDERIZADO VISTA MENSUAL (Detallada) ---
        function renderMonthGrid() {
            const grid = document.getElementById('monthGrid');
            grid.innerHTML = '';

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            // Rellenar días previos
            for (let i = firstDay - 1; i >= 0; i--) {
                const cell = createMonthCell(daysInPrevMonth - i, true);
                grid.appendChild(cell);
            }

            // Días actuales
            for (let day = 1; day <= daysInMonth; day++) {
                const cell = createMonthCell(day, false);
                
                // Eventos
                const dayEvents = events.filter(e => {
                    const eDate = new Date(e.fecha_inicio);
                    return eDate.getDate() === day && eDate.getMonth() === month && eDate.getFullYear() === year;
                });

                // Renderizar pastillas de eventos
                dayEvents.forEach(event => {
                    const eventEl = document.createElement('div');
                    eventEl.className = 'mt-1 px-2 py-0.5 text-[10px] font-medium rounded cursor-pointer truncate transition-all hover:opacity-80';
                    
                    // Colores de estado
                    const now = new Date();
                    const start = new Date(event.fecha_inicio);
                    if (now < start) {
                        eventEl.classList.add('bg-blue-100', 'text-blue-700', 'dark:bg-blue-500/20', 'dark:text-blue-300');
                    } else if (now > new Date(event.fecha_fin)) {
                        eventEl.classList.add('bg-gray-100', 'text-gray-600', 'dark:bg-gray-700', 'dark:text-gray-400');
                    } else {
                        eventEl.classList.add('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-500/30', 'dark:text-indigo-300', 'border-l-2', 'border-indigo-500');
                    }
                    
                    eventEl.textContent = event.nombre;
                    eventEl.onclick = (e) => {
                        e.stopPropagation();
                        window.location.href = `/admin/eventos/${event.id}/`;
                    };
                    eventEl.onmouseenter = (e) => { clearTimeout(tooltipTimeout); showTooltip(e, event); };
                    eventEl.onmouseleave = () => { tooltipTimeout = setTimeout(hideTooltip, 300); };

                    cell.appendChild(eventEl);
                });

                grid.appendChild(cell);
            }

            // Rellenar días siguientes para cuadrar
            const totalCells = grid.children.length;
            const remainingCells = 42 - totalCells; 
            for (let i = 1; i <= remainingCells; i++) {
                const cell = createMonthCell(i, true);
                grid.appendChild(cell);
            }
        }

        function createMonthCell(dayNumber, isGray) {
            const cell = document.createElement('div');
            cell.className = `min-h-[8rem] p-2 bg-white dark:bg-[#1a222c] transition-colors ${isGray ? 'bg-gray-50/50 dark:bg-[#151b23] text-gray-400 dark:text-gray-600' : 'hover:bg-gray-50 dark:hover:bg-[#24303f]'}`;
            
            const dateNum = document.createElement('div');
            dateNum.textContent = dayNumber;
            dateNum.className = `text-sm font-medium mb-1 ${isGray ? '' : 'text-gray-700 dark:text-gray-300'}`;

            // Highlight Hoy
            const today = new Date();
            if (!isGray && dayNumber === today.getDate() && currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear()) {
                dateNum.className = 'w-7 h-7 flex items-center justify-center bg-indigo-600 text-white rounded-full text-sm font-bold mb-1 shadow-lg shadow-indigo-500/50';
            }

            cell.appendChild(dateNum);
            return cell;
        }


        // --- RENDERIZADO VISTA ANUAL (Estilo Imagen) ---
        function renderYearGrid() {
            const grid = document.getElementById('yearGrid');
            grid.innerHTML = '';

            monthNames.forEach((name, monthIndex) => {
                // Contenedor del Mes
                const monthCard = document.createElement('div');
                // Sin bordes, fondo transparente o muy sutil para que se vea limpio
                monthCard.className = 'p-2'; 

                // Título del Mes
                const title = document.createElement('h4');
                title.textContent = name;
                title.className = 'text-center font-bold text-gray-800 dark:text-gray-200 mb-4 cursor-pointer hover:text-indigo-500 transition-colors';
                title.onclick = () => {
                    currentDate.setMonth(monthIndex);
                    viewMode = 'month';
                    render();
                };
                monthCard.appendChild(title);

                // Cabecera de días (D L M M J V S)
                const daysHeader = document.createElement('div');
                daysHeader.className = 'grid grid-cols-7 mb-2';
                dayInitials.forEach(initial => {
                    const d = document.createElement('div');
                    d.textContent = initial;
                    d.className = 'text-center text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase';
                    daysHeader.appendChild(d);
                });
                monthCard.appendChild(daysHeader);

                // Rejilla de días del mes
                const miniGrid = document.createElement('div');
                miniGrid.className = 'grid grid-cols-7 gap-y-1'; // Gap vertical pequeño, horizontal 0

                const year = currentDate.getFullYear();
                const firstDay = new Date(year, monthIndex, 1).getDay();
                const daysInMonth = new Date(year, monthIndex + 1, 0).getDate();

                // Espacios vacíos iniciales
                for (let i = 0; i < firstDay; i++) {
                    miniGrid.appendChild(document.createElement('div'));
                }

                // Días
                for (let day = 1; day <= daysInMonth; day++) {
                    const dayCell = document.createElement('div');
                    dayCell.textContent = day;
                    
                    // Estilo base del número
                    let cellClass = 'h-8 w-8 mx-auto flex items-center justify-center text-xs rounded-full cursor-pointer transition-all duration-200 ';
                    
                    // Verificar eventos
                    const hasEvents = events.some(e => {
                        const eDate = new Date(e.fecha_inicio);
                        return eDate.getDate() === day && eDate.getMonth() === monthIndex && eDate.getFullYear() === year;
                    });

                    const isToday = (day === new Date().getDate() && monthIndex === new Date().getMonth() && year === new Date().getFullYear());

                    if (isToday) {
                        // Estilo HOY (Círculo Azul Brillante como en la imagen)
                        cellClass += 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-500/50';
                    } else if (hasEvents) {
                        // Días con eventos (Sutilmente marcados)
                        cellClass += 'text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-800';
                    } else {
                        // Días normales
                        cellClass += 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white';
                    }

                    dayCell.className = cellClass;
                    
                    // Click para ir a ver ese día (cambia a mes)
                    dayCell.onclick = (e) => {
                        e.stopPropagation();
                        currentDate.setMonth(monthIndex);
                        currentDate.setDate(day); // Opcional: podrías resaltar el día al cambiar
                        viewMode = 'month';
                        render();
                    };

                    // Agregar tooltips si el día tiene eventos
                    if (hasEvents) {
                        const dayEvents = events.filter(e => {
                            const eDate = new Date(e.fecha_inicio);
                            return eDate.getDate() === day && eDate.getMonth() === monthIndex && eDate.getFullYear() === year;
                        });
                        
                        // Mostrar tooltip con el primer evento del día
                        dayCell.onmouseenter = (e) => {
                            clearTimeout(tooltipTimeout);
                            showTooltip(e, dayEvents[0]);
                        };
                        dayCell.onmouseleave = () => {
                            tooltipTimeout = setTimeout(hideTooltip, 300);
                        };
                    }

                    miniGrid.appendChild(dayCell);
                }

                monthCard.appendChild(miniGrid);
                grid.appendChild(monthCard);
            });
        }

        // --- CONTROLES ---
        document.getElementById('prevBtn').addEventListener('click', () => {
            if (viewMode === 'month') currentDate.setMonth(currentDate.getMonth() - 1);
            else currentDate.setFullYear(currentDate.getFullYear() - 1);
            render();
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            if (viewMode === 'month') currentDate.setMonth(currentDate.getMonth() + 1);
            else currentDate.setFullYear(currentDate.getFullYear() + 1);
            render();
        });

        document.getElementById('todayBtn').addEventListener('click', () => {
            currentDate = new Date();
            render();
        });

        document.getElementById('viewSelector').addEventListener('change', (e) => {
            viewMode = e.target.value;
            render();
        });

        // --- TOOLTIP (Mismo de antes) ---
        const tooltip = document.createElement('div');
        tooltip.className = 'fixed hidden z-50 w-64 bg-white dark:bg-[#24303f] rounded-lg shadow-2xl p-4 border border-gray-200 dark:border-gray-600 text-sm pointer-events-none';
        document.body.appendChild(tooltip);

        function showTooltip(e, event) {
            const rect = e.target.getBoundingClientRect();
            let left = rect.left;
            if (left + 256 > window.innerWidth) left = window.innerWidth - 270;
            
            tooltip.style.left = `${left}px`;
            tooltip.style.top = `${rect.bottom + 10}px`;
            tooltip.classList.remove('hidden');
            
            const start = new Date(event.fecha_inicio).toLocaleDateString();
            const end = new Date(event.fecha_fin).toLocaleDateString();

            tooltip.innerHTML = `
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">${event.nombre}</h4>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-xs mb-3 line-clamp-2 leading-relaxed">${event.descripcion || 'Sin descripción'}</p>
                <div class="text-[10px] uppercase tracking-wider font-semibold text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-gray-700 pt-2">
                    ${start} - ${end}
                </div>
            `;
        }
        function hideTooltip() { tooltip.classList.add('hidden'); }

        // Iniciar
        render();
    </script>
</x-app-layout>