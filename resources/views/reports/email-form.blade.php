@extends('layouts.app')

@section('title', 'Enviar Email a Graduados')

@section('header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Enviar Email a Graduados</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Selecciona destinatarios y personaliza tu mensaje</p>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Email Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <form action="{{ route('reports.send-email') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Recipients Section -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Destinatarios</h2>
                        
                        <div class="space-y-4">
                            <!-- Graduation Year Filter -->
                            <div>
                                <label for="graduation_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Año de Graduación
                                </label>
                                <select id="graduation_year" name="graduation_year" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                                <select id="career_id" name="career_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Todas las carreras</option>
                                    @foreach ($careers as $career)
                                        @php
                                            $selected = $filters['career_id'] == $career->id ? 'selected' : '';
                                        @endphp
                                        <option value="{{ $career->id }}" {{ $selected }}>{{ $career->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Gender Filter -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Género
                                </label>
                                <select id="gender" name="gender" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Todos los géneros</option>
                                    @php
                                        $maleSelected = $filters['gender'] == 'male' ? 'selected' : '';
                                        $femaleSelected = $filters['gender'] == 'female' ? 'selected' : '';
                                    @endphp
                                    <option value="male" {{ $maleSelected }}>Masculino</option>
                                    <option value="female" {{ $femaleSelected }}>Femenino</option>
                                </select>
                            </div>

                            <!-- Recipients Count (will be updated via JS) -->
                            <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    <span id="recipient-count" class="font-bold">Cargando...</span> graduados serán contactados
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Content Section -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contenido del Email</h2>
                        
                        <div class="space-y-4">
                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Asunto <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="subject" name="subject" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Ej: Actualización importante para graduados"
                                    value="{{ old('subject') }}">
                                @error('subject')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Message Body -->
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Mensaje <span class="text-red-500">*</span>
                                </label>
                                <textarea id="message" name="message" required rows="10"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Escribe tu mensaje aquí...">{{ old('message') }}</textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Las variables {{ '{'{'{name}'}'} }}, {{ '{'{'{email}'}'} }} y {{ '{'{'{graduation_year}'}'} }} se reemplazarán automáticamente</p>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            Enviar Email a Graduados
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Section -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vista Previa</h2>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Asunto:</p>
                        <p id="preview-subject" class="text-gray-900 dark:text-white font-semibold truncate">-</p>
                    </div>

                    <div>
                        <p class="text-gray-600 dark:text-gray-400">De:</p>
                        <p class="text-gray-900 dark:text-white">{{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
                    </div>

                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Mensaje (primeras líneas):</p>
                        <p id="preview-message" class="text-gray-900 dark:text-white text-xs line-clamp-6 whitespace-pre-wrap">-</p>
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Los siguientes tags se reemplazarán en cada email:
                        </p>
                        <ul class="text-xs text-gray-600 dark:text-gray-400 mt-2 space-y-1">
                            <li>{{ '{'{'{name}'}'} }} - Nombre completo del graduado</li>
                            <li>{{ '{'{'{email}'}'} }} - Email del graduado</li>
                            <li>{{ '{'{'{graduation_year}'}'} }} - Año de graduación</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update preview when subject changes
        document.getElementById('subject').addEventListener('input', function() {
            document.getElementById('preview-subject').textContent = this.value || '-';
        });

        // Update preview when message changes
        document.getElementById('message').addEventListener('input', function() {
            document.getElementById('preview-message').textContent = this.value || '-';
        });

        // Update recipient count when filters change
        async function updateRecipientCount() {
            const filters = {
                graduation_year: document.getElementById('graduation_year').value,
                career_id: document.getElementById('career_id').value,
                gender: document.getElementById('gender').value,
            };

            try {
                const response = await fetch(`/dashboard/data/graduates-count?${new URLSearchParams(filters)}`);
                const data = await response.json();
                document.getElementById('recipient-count').textContent = data.count || 0;
            } catch (error) {
                console.error('Error fetching recipient count:', error);
            }
        }

        document.getElementById('graduation_year').addEventListener('change', updateRecipientCount);
        document.getElementById('career_id').addEventListener('change', updateRecipientCount);
        document.getElementById('gender').addEventListener('change', updateRecipientCount);

        // Initial load
        updateRecipientCount();
    </script>
@endsection
