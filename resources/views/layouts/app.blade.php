<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeManager()" :class="{ dark: darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="color-scheme" content="light dark">

        <title>@yield('title') - {{ config('app.name', 'Dashboard') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Dark Mode Initialization (prevents flash of unstyled content) -->
        <script>
            (function() {
                // Get saved preference or system preference
                const saved = localStorage.getItem('darkMode');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isDark = saved !== null ? saved === 'true' : prefersDark;
                
                // Apply dark mode immediately
                if (isDark) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navbar -->
                @include('layouts.topbar')

                <!-- Page Content -->
                <main class="flex-1 overflow-auto">
                    <div class="py-6 px-4 sm:px-6 lg:px-8">
                        <!-- Page Header -->
                        @hasSection('header')
                            <div class="mb-6">
                                @yield('header')
                            </div>
                        @endif

                        <!-- Page Content -->
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>

        <!-- Global Alert Handler -->
        <script>
            // Get dark mode state
            const isDarkModeCheck = () => document.documentElement.classList.contains('dark');

            // SweetAlert2 configuration for dark mode
            const getAlertConfig = () => ({
                background: isDarkModeCheck() ? '#1f2937' : '#ffffff',
                color: isDarkModeCheck() ? '#f3f4f6' : '#111827',
            });

            // Show success messages from session
            @if (session('success'))
                Swal.fire({
                    ...getAlertConfig(),
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            @endif

            // Show error messages from session
            @if (session('error'))
                Swal.fire({
                    ...getAlertConfig(),
                    title: 'Error',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    position: 'center',
                    confirmButtonColor: '#ef4444',
                });
            @endif

            // Intercept delete forms for confirmation
            document.addEventListener('DOMContentLoaded', function () {
                const deleteForms = document.querySelectorAll('form[data-confirm-delete]');
                
                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        
                        Swal.fire({
                            ...getAlertConfig(),
                            title: '¿Estás seguro?',
                            text: 'Esta acción no se puede deshacer.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>
