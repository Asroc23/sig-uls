@extends('layouts.app')

@section('title', $event->name)

@section('header')
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $event->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $event->career->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('events.downloadPdf', $event) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF
            </a>
            <a href="{{ route('events.edit', $event) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                Editar
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Event Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Image -->
            @if ($event->image_path)
                <div class="rounded-lg overflow-hidden h-96">
                    <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Event Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Información del Evento</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Fecha y Hora</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $event->formatted_date_only }}</p>
                        <p class="text-gray-600 dark:text-gray-400">{{ $event->formatted_time }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Carrera</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $event->career->name }}</p>
                    </div>
                </div>

                @if ($event->description)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Descripción</p>
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $event->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Add Participant -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Agregar Participante</h2>
                <form id="addParticipantForm" class="space-y-4">
                    <!-- Autocomplete Search Input -->
                    <div class="relative">
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Busca por nombre o carnet..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            autocomplete="off"
                        >
                        <input type="hidden" id="graduateId" name="graduate_id">
                        <div id="searchResults" class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg z-10 hidden max-h-48 overflow-y-auto"></div>
                    </div>

                    <button 
                        type="button"
                        id="addButton"
                        disabled
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Agregar
                    </button>
                </form>
            </div>
        </div>

        <!-- Participants List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 h-fit">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Participantes ({{ $event->graduates->count() }})</h2>
            
            @if ($event->graduates->count() > 0)
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach ($event->graduates as $graduate)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $graduate->full_name }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $graduate->carnet }}</p>
                            </div>
                            <button 
                                type="button"
                                onclick="removeParticipant({{ $event->id }}, {{ $graduate->id }})"
                                class="ml-2 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 opacity-0 group-hover:opacity-100 transition-opacity"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-600 dark:text-gray-400 py-8">No hay participantes aún</p>
            @endif
        </div>
    </div>

    <script>
        // Elements
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const graduateId = document.getElementById('graduateId');
        const addButton = document.getElementById('addButton');
        
        // Debounce timer
        let searchTimeout = null;
        let currentHighlightIndex = -1;

        // Debounce search function (300ms)
        function debounceSearch(fn, delay = 300) {
            return function(...args) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => fn(...args), delay);
            };
        }

        // Professional autocomplete search with debounce
        const performSearch = debounceSearch(async (query) => {
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                currentHighlightIndex = -1;
                return;
            }

            try {
                const url = `{{ route('events.searchGraduates') }}?q=${encodeURIComponent(query)}`;
                const response = await fetch(url);
                
                if (!response.ok) {
                    console.error('Search failed:', response.status);
                    return;
                }

                const graduates = await response.json();

                if (graduates.length === 0) {
                    searchResults.innerHTML = '<div class="p-3 text-sm text-gray-500 dark:text-gray-400 text-center">Sin resultados</div>';
                    searchResults.classList.remove('hidden');
                    currentHighlightIndex = -1;
                    return;
                }

                // Render professional dropdown UI
                searchResults.innerHTML = graduates.map((g, idx) => `
                    <div class="search-result-item" data-id="${g.id}" data-index="${idx}" onclick="selectGraduate(${g.id}, '${g.carnet} - ${g.first_name} ${g.last_name}')">
                        <div class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-blue-900/30 cursor-pointer transition-colors rounded">
                            <p class="font-medium text-gray-900 dark:text-white text-sm">${g.first_name} ${g.last_name}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${g.carnet}</p>
                        </div>
                    </div>
                `).join('');
                
                searchResults.classList.remove('hidden');
                currentHighlightIndex = -1;
            } catch (error) {
                console.error('Search error:', error);
            }
        });

        // Search input listener with debounce
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                performSearch(e.target.value.trim());
            });

            // Keyboard navigation
            searchInput.addEventListener('keydown', (e) => {
                const items = searchResults.querySelectorAll('.search-result-item');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    currentHighlightIndex = Math.min(currentHighlightIndex + 1, items.length - 1);
                    highlightItem(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    currentHighlightIndex = Math.max(currentHighlightIndex - 1, -1);
                    highlightItem(items);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (currentHighlightIndex >= 0 && items[currentHighlightIndex]) {
                        items[currentHighlightIndex].click();
                    }
                }
            });
        }

        function highlightItem(items) {
            items.forEach((item, idx) => {
                if (idx === currentHighlightIndex) {
                    item.querySelector('div').classList.add('bg-blue-100', 'dark:bg-blue-900/50');
                } else {
                    item.querySelector('div').classList.remove('bg-blue-100', 'dark:bg-blue-900/50');
                }
            });
        }

        function selectGraduate(id, name) {
            graduateId.value = id;
            if (searchInput) {
                searchInput.value = name;
            }
            searchResults.classList.add('hidden');
            addButton.disabled = false;
            currentHighlightIndex = -1;
        }

        document.getElementById('addParticipantForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            await addParticipant();
        });

        addButton.addEventListener('click', addParticipant);

        async function addParticipant() {
            const id = graduateId.value;
            
            if (!id) {
                Swal.fire({
                    ...getAlertConfig(),
                    title: 'Advertencia',
                    text: 'Por favor selecciona un graduado',
                    icon: 'warning',
                });
                return;
            }

            try {
                const response = await fetch(`{{ route('events.attachGraduate', $event) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ graduate_id: id })
                });

                const data = await response.json();
                console.log('Add response:', data);

                if (response.ok) {
                    Swal.fire({
                        ...getAlertConfig(),
                        title: '¡Éxito!',
                        text: data.message,
                        icon: 'success',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                    });
                    // Reset form
                    if (searchInput) {
                        searchInput.value = '';
                    }
                    graduateId.value = '';
                    addButton.disabled = true;
                    window.location.reload();
                } else {
                    Swal.fire({
                        ...getAlertConfig(),
                        title: 'Error',
                        text: data.message || 'Error al agregar el participante',
                        icon: 'error',
                        position: 'center',
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    ...getAlertConfig(),
                    title: 'Error',
                    text: 'Error al agregar el participante',
                    icon: 'error',
                    position: 'center',
                });
            }
        }

        async function removeParticipant(eventId, graduateId) {
            const result = await Swal.fire({
                ...getAlertConfig(),
                title: '¿Estás seguro?',
                text: 'Esto eliminará el graduado del evento.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            });

            if (!result.isConfirmed) return;

            try {
                const response = await fetch(`{{ route('events.detachGraduate', $event) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ graduate_id: graduateId })
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        ...getAlertConfig(),
                        title: '¡Eliminado!',
                        text: data.message,
                        icon: 'success',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        ...getAlertConfig(),
                        title: 'Error',
                        text: data.message || 'Error al eliminar el participante',
                        icon: 'error',
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    ...getAlertConfig(),
                    title: 'Error',
                    text: 'Error al eliminar el participante',
                    icon: 'error',
                });
            }
        }

        // Close search results when clicking outside
        document.addEventListener('click', (e) => {
            const isClickInsideSearch = searchInput && e.target.closest('#searchInput');
            const isClickInsideResults = searchResults && e.target.closest('#searchResults');
            
            if (!isClickInsideSearch && !isClickInsideResults && searchResults) {
                searchResults.classList.add('hidden');
                currentHighlightIndex = -1;
            }
        });
    </script>
@endsection
