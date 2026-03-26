@extends('layouts.app')

@section('title', 'Editar Evento')

@section('header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editar Evento</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $event->name }}</p>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre del Evento <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="{{ old('name', $event->name) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Career -->
                <div>
                    <label for="career_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Carrera <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="career_id" 
                        id="career_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        @foreach ($careers as $career)
                            <option value="{{ $career->id }}" @selected(old('career_id', $event->career_id) == $career->id)>
                                {{ $career->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('career_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fecha y Hora <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="datetime-local" 
                        name="event_date" 
                        id="event_date"
                        value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    @error('event_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Imagen del Evento
                    </label>
                    
                    @if ($event->image_path)
                        <div class="mb-4">
                            <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="h-32 rounded-lg object-cover">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Imagen actual</p>
                        </div>
                    @endif

                    <input 
                        type="file" 
                        name="image" 
                        id="image"
                        accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Deja en blanco para mantener la imagen actual</p>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descripción
                    </label>
                    <textarea 
                        name="description" 
                        id="description"
                        rows="5"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Guardar Cambios
                    </button>
                    <a 
                        href="{{ route('events.show', $event) }}" 
                        class="flex-1 px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors font-medium text-center"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
