@extends('layouts.app')

@section('title', $graduate->full_name)

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $graduate->full_name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $graduate->career->name ?? 'Sin carrera' }}</p>
        </div>
        <div class="space-x-3">
            <a href="{{ route('graduates.edit', $graduate) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </a>
            <form action="{{ route('graduates.destroy', $graduate) }}" method="POST" class="inline-block" data-confirm-delete>
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Eliminar
                </button>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Photo Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            @if ($graduate->photo_path)
                <img src="{{ $graduate->photo_url }}" alt="{{ $graduate->full_name }}" class="w-full h-64 object-cover rounded-lg mb-4">
            @else
                <div class="w-full h-64 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center mb-4">
                    <span class="text-4xl font-bold text-gray-700 dark:text-gray-300">{{ substr($graduate->first_name, 0, 1) }}{{ substr($graduate->last_name, 0, 1) }}</span>
                </div>
            @endif
            <div class="space-y-2">
                <p class="text-sm text-gray-600 dark:text-gray-400">Registrado hace:</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $graduate->created_at->diffForHumans() }}</p>
            </div>
        </div>

        <!-- Details Card -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información Personal</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Nombre</p>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $graduate->first_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Apellido</p>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $graduate->last_name }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                        <a href="mailto:{{ $graduate->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $graduate->email }}</a>
                    </div>
                    @if ($graduate->phone)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Teléfono</p>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $graduate->phone }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información Académica</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Carrera</p>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $graduate->career->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Año de Graduación</p>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $graduate->graduation_year }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Género</p>
                            <p class="text-gray-900 dark:text-white font-medium">
                                @if ($graduate->gender === 'male')
                                    Masculino
                                @else
                                    Femenino
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($graduate->career && $graduate->career->description)
                <!-- Career Description -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Descripción de la Carrera</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $graduate->career->description }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('graduates.index') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver a Graduados
        </a>
    </div>
@endsection
