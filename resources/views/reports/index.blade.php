@extends('layouts.app')

@section('title', 'Reporte de Graduados')

@section('header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reporte de Graduados</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Filtra y descarga reportes de graduados</p>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Filters Section -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-20">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filtros</h2>
                
                <form method="GET" action="{{ route('reports.index') }}" id="filterForm" class="space-y-4">
                    <!-- Graduation Year Filter -->
                    <div>
                        <label for="graduation_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Año de Graduación
                        </label>
                        <select 
                            id="graduation_year" 
                            name="graduation_year"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Todos los años</option>
                            @php
                                $currentYear = now()->year;
                                for ($year = $currentYear; $year >= 2000; $year--) {
                                    $selected = $filters['graduation_year'] == $year ? 'selected' : '';
                                    echo "<option value=\"$year\" $selected>$year</option>";
                                }
                            @endphp
                        </select>
                    </div>

                    <!-- Career Filter -->
                    <div>
                        <label for="career_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Carrera
                        </label>
                        <select 
                            id="career_id" 
                            name="career_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Todas las carreras</option>
                            @foreach ($careers as $career)
                                <option value="{{ $career->id }}" {{ $filters['career_id'] == $career->id ? 'selected' : '' }}>
                                    {{ $career->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender Filter -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Género
                        </label>
                        <select 
                            id="gender" 
                            name="gender"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Todos los géneros</option>
                            <option value="male" {{ $filters['gender'] == 'male' ? 'selected' : '' }}>Masculino</option>
                            <option value="female" {{ $filters['gender'] == 'female' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2 pt-2">
                        <button 
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ver Resultados
                        </button>
                        
                        <a
                            href="{{ route('reports.pdf', ['graduation_year' => $filters['graduation_year'], 'career_id' => $filters['career_id'], 'gender' => $filters['gender']]) }}"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 no-underline"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Descargar PDF
                        </a>

                        <a
                            href="{{ route('reports.excel', request()->all()) }}"
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 no-underline"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Descargar Excel
                        </a>
                        
                        <a
                            href="{{ route('reports.index') }}"
                            class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 no-underline"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Limpiar Filtros
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        <div class="lg:col-span-3">
            <!-- Summary Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Resultados encontrados</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalResults }}</p>
                    </div>
                    <div class="text-5xl text-blue-500">📋</div>
                </div>

                @if ($filters['graduation_year'] || $filters['career_id'] || $filters['gender'])
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Filtros aplicados:</p>
                        <div class="flex flex-wrap gap-2">
                            @if ($filters['graduation_year'])
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                    Año: {{ $filters['graduation_year'] }}
                                </span>
                            @endif
                            @if ($filters['career_id'])
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                    Carrera: {{ $careers->find($filters['career_id'])?->name }}
                                </span>
                            @endif
                            @if ($filters['gender'])
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-200">
                                    Género: {{ $filters['gender'] == 'male' ? 'Masculino' : 'Femenino' }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Results Table -->
            @if ($totalResults > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Carrera
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Año
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Género
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Email
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($graduates as $graduate)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                                            {{ $graduate->first_name }} {{ $graduate->last_name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $graduate->career?->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $graduate->graduation_year }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            @if ($graduate->gender === 'male')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                    Masculino
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-200">
                                                    Femenino
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            <a href="mailto:{{ $graduate->email }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $graduate->email }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">
                        No hay resultados que coincidan con los filtros seleccionados
                    </p>
                    <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">
                        Intenta cambiar los filtros para ver más resultados
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
