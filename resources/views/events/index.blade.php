@extends('layouts.app')

@section('title', 'Eventos')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Eventos</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gestiona eventos de graduación y reuniones</p>
        </div>
        <a href="{{ route('events.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Evento
        </a>
    </div>
@endsection

@section('content')
    @if ($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($events as $event)
                <a href="{{ route('events.show', $event) }}" class="group">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-all overflow-hidden">
                        @if ($event->image_path)
                            <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="w-full h-48 object-cover group-hover:opacity-75 transition-opacity">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $event->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $event->career->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $event->formatted_date }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                {{ $event->graduates->count() }} participantes
                            </p>
                        </div>

                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 flex gap-2 justify-end">
                            <a href="{{ route('events.edit', $event) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">
                                Editar
                            </a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" data-confirm-delete>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No hay eventos</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Comienza creando un nuevo evento para el sistema.</p>
            <a href="{{ route('events.create') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                Crear Evento
            </a>
        </div>
    @endif
@endsection
