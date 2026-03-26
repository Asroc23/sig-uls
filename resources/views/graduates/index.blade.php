@extends('layouts.app')

@section('title', 'Graduados')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Graduados</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gestionar graduados y sus perfiles</p>
        </div>
        <a href="{{ route('graduates.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Graduado
        </a>
    </div>
@endsection

@section('content')
    <!-- Filters Form -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form method="GET" action="{{ route('graduates.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                    <input 
                        type="text" 
                        name="search" 
                        id="search" 
                        value="{{ request('search') }}"
                        placeholder="Por nombre o carnet..." 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Graduation Year Filter -->
                <div>
                    <label for="graduation_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Año Graduación</label>
                    <select 
                        name="graduation_year" 
                        id="graduation_year"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Todos</option>
                        @php
                            $currentYear = date('Y');
                            for ($year = $currentYear; $year >= 2000; $year--) {
                                $selected = request('graduation_year') == $year ? 'selected' : '';
                                echo "<option value=\"$year\" $selected>$year</option>";
                            }
                        @endphp
                    </select>
                </div>

                <!-- Career Filter -->
                <div>
                    <label for="career_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Carrera</label>
                    <select 
                        name="career_id" 
                        id="career_id"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Todas</option>
                        @foreach ($careers as $career)
                            <option value="{{ $career->id }}" {{ request('career_id') == $career->id ? 'selected' : '' }}>
                                {{ $career->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gender Filter -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Género</label>
                    <select 
                        name="gender" 
                        id="gender"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Todos</option>
                        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Masculino</option>
                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>

                <!-- Buttons Container -->
                <div class="flex items-end gap-2">
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Filtrar
                    </button>
                    @if (request()->anyFilled(['search', 'graduation_year', 'career_id', 'gender']))
                        <a 
                            href="{{ route('graduates.index') }}" 
                            class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-700 transition-colors font-medium text-center"
                        >
                            Limpiar
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Carnet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Carrera</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Año</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($graduates as $graduate)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($graduate->photo_path)
                                        <img src="{{ $graduate->photo_url }}" alt="{{ $graduate->full_name }}" class="h-10 w-10 rounded-full object-cover mr-3">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center mr-3">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ substr($graduate->first_name, 0, 1) }}{{ substr($graduate->last_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $graduate->full_name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            @if ($graduate->gender === 'male')
                                                Masculino
                                            @else
                                                Femenino
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400">
                                    {{ $graduate->carnet }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $graduate->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $graduate->career->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $graduate->graduation_year }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('graduates.show', $graduate) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-md hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                    Ver
                                </a>
                                <a href="{{ route('graduates.edit', $graduate) }}" class="inline-flex items-center px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-md hover:bg-amber-200 dark:hover:bg-amber-900/50 transition-colors">
                                    Editar
                                </a>
                                <form action="{{ route('graduates.destroy', $graduate) }}" method="POST" class="inline-block" data-confirm-delete>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-md hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No hay graduados registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($graduates->hasPages())
            <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $graduates->links() }}
            </div>
        @endif
    </div>
@endsection
