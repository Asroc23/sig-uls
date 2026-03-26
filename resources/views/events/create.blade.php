@extends('layouts.app')

@section('title', 'Crear Evento')

@section('header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Crear Evento</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Añade un nuevo evento al sistema</p>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre del Evento <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ej: Reunión de Egresados 2025"
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
                        <option value="">Selecciona una carrera</option>
                        @foreach ($careers as $career)
                            <option value="{{ $career->id }}" @selected(old('career_id') == $career->id)>
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
                        value="{{ old('event_date') }}"
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
                    <input 
                        type="file" 
                        name="image" 
                        id="image"
                        accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Máximo 2MB. Formatos: JPEG, PNG, JPG, GIF</p>
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
                        placeholder="Descripción del evento..."
                    >{{ old('description') }}</textarea>
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
                        Crear Evento
                    </button>
                    <a 
                        href="{{ route('events.index') }}" 
                        class="flex-1 px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors font-medium text-center"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
