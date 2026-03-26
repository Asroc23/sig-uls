@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mi Perfil</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Administra tu información de cuenta y configuración de seguridad</p>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Avatar Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-6">
                <div class="text-center">
                    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-4xl font-bold mb-4">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Auth::user()->email }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-3">
                        Miembro desde {{ Auth::user()->created_at->format('d de M, Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Forms Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Update Profile Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Información de Perfil</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Actualiza tu nombre y correo electrónico</p>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            value="{{ old('name', Auth::user()->name) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Tu nombre completo"
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            value="{{ old('email', Auth::user()->email) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="tu@email.com"
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4">
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                        >
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- Update Password -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Cambiar Contraseña</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Usa una contraseña segura y única para proteger tu cuenta</p>

                <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contraseña Actual <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="current_password" 
                            id="current_password"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ingresa tu contraseña actual"
                        >
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nueva Contraseña <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Mínimo 8 caracteres"
                        >
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirmar Contraseña <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Confirma tu nueva contraseña"
                        >
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4">
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                        >
                            Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-red-500">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Eliminar Cuenta</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Una vez eliminada tu cuenta, todos tus datos serán borrados permanentemente. Esta acción no se puede deshacer.
                </p>

                <form action="{{ route('profile.destroy') }}" method="POST" data-confirm-delete>
                    @csrf
                    @method('DELETE')

                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <label for="delete_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirma tu Contraseña <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="delete_password"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Ingresa tu contraseña para confirmar"
                        >
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                    >
                        Eliminar mi Cuenta
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
