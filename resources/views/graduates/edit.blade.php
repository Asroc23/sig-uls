@extends('layouts.app')

@section('title', 'Editar Graduado')

@section('header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editar Graduado</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $graduate->full_name }}</p>
    </div>
@endsection

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('graduates.update', $graduate) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Photo Upload -->
            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto</label>
                <div class="relative">
                    @if ($graduate->photo_path)
                        <img id="currentPhoto" src="{{ $graduate->photo_url }}" alt="{{ $graduate->full_name }}" class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700 mb-2">
                    @endif
                    <input type="file" name="photo" id="photo" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900 file:text-blue-700 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-800" onchange="previewImage(this)">
                    <img id="photoPreview" class="hidden mt-2 w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700" alt="Preview">
                </div>
                @error('photo')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Carnet -->
            <div>
                <label for="carnet" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Carnet</label>
                <input type="text" name="carnet" id="carnet" value="{{ old('carnet', $graduate->carnet) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                @error('carnet')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $graduate->first_name) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                @error('first_name')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Apellido</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $graduate->last_name) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                @error('last_name')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $graduate->email) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                @error('email')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teléfono (Opcional)</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $graduate->phone) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('phone')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gender -->
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Género</label>
                <select name="gender" id="gender" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">Seleccionar género</option>
                    <option value="male" {{ old('gender', $graduate->gender) === 'male' ? 'selected' : '' }}>Masculino</option>
                    <option value="female" {{ old('gender', $graduate->gender) === 'female' ? 'selected' : '' }}>Femenino</option>
                </select>
                @error('gender')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Graduation Year -->
            <div>
                <label for="graduation_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Año de Graduación</label>
                <input type="number" name="graduation_year" id="graduation_year" value="{{ old('graduation_year', $graduate->graduation_year) }}" min="1900" max="{{ date('Y') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                @error('graduation_year')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Career -->
            <div>
                <label for="career_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Carrera</label>
                <select name="career_id" id="career_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">Seleccionar carrera</option>
                    @foreach ($careers as $career)
                        <option value="{{ $career->id }}" {{ old('career_id', $graduate->career_id) == $career->id ? 'selected' : '' }}>{{ $career->name }}</option>
                    @endforeach
                </select>
                @error('career_id')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Actualizar Graduado
                </button>
                <a href="{{ route('graduates.index') }}" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('photoPreview');
            const currentPhoto = document.getElementById('currentPhoto');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (currentPhoto) currentPhoto.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
