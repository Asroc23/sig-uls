@extends('layouts.app')

@section('title', 'Dashboard de Graduados')

@section('header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard de Graduados</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Análisis estadístico de graduados</p>
    </div>
@endsection

@section('content')
    <!-- Filters Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filtros</h2>
            <div class="flex gap-2">
                <form id="pdfForm" method="GET" action="{{ route('reports.download-pdf') }}" style="display:inline;">
                    <button type="button" onclick="submitPdfForm()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Descargar PDF
                    </button>
                </form>
                <a href="#" onclick="goToEmailWithFilters(event)" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Enviar Email
                </a>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Graduation Year Filter -->
            <div>
                <label for="graduation_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Año de Graduación
                </label>
                <select id="graduation_year" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Todos los años</option>
                    @php
                        $currentYear = now()->year;
                        for ($year = $currentYear; $year >= 2000; $year--) {
                            echo "<option value=\"$year\">$year</option>";
                        }
                    @endphp
                </select>
            </div>

            <!-- Career Filter -->
            <div>
                <label for="career_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Carrera
                </label>
                <select id="career_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Todas las carreras</option>
                    @foreach ($careers as $career)
                        <option value="{{ $career->id }}">{{ $career->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Gender Filter -->
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Género
                </label>
                <select id="gender" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Todos los géneros</option>
                    <option value="male">Masculino</option>
                    <option value="female">Femenino</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graduates by Year Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Graduados por Año</h3>
            <div class="relative h-80">
                <canvas id="byYearChart"></canvas>
            </div>
        </div>

        <!-- Graduates by Gender Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Graduados por Género</h3>
            <div class="relative h-80 flex items-center justify-center">
                <canvas id="byGenderChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Graduates by Career Chart (Full Width) -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Graduados por Carrera</h3>
        <div class="relative h-80">
            <canvas id="byCareerChart"></canvas>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

    <script>
        // Initialize charts
        let byYearChart, byGenderChart, byCareerChart;

        // Color schemes for charts
        const colors = {
            primary: '#3b82f6',
            secondary: '#10b981',
            tertiary: '#f59e0b',
            danger: '#ef4444',
            purple: '#8b5cf6',
            pink: '#ec4899',
        };

        const chartColors = [
            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
            '#ec4899', '#14b8a6', '#f97316', '#06b6d4', '#84cc16'
        ];

        // Fetch data and update charts
        async function updateCharts() {
            const filters = getFilterValues();

            try {
                // Fetch data from new data endpoints
                const [byYearData, byGenderData, byCareerData] = await Promise.all([
                    fetchChartData('/dashboard/data/by-year', filters),
                    fetchChartData('/dashboard/data/by-gender', filters),
                    fetchChartData('/dashboard/data/by-career', filters),
                ]);

                // Update charts
                updateByYearChart(byYearData);
                updateByGenderChart(byGenderData);
                updateByCareerChart(byCareerData);
            } catch (error) {
                console.error('Error fetching chart data:', error);
            }
        }

        // Fetch data from API
        async function fetchChartData(url, filters) {
            const queryString = new URLSearchParams(filters).toString();
            const response = await fetch(`${url}?${queryString}`, {
                headers: {
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return response.json();
        }

        // Get filter values
        function getFilterValues() {
            const filters = {};

            const graduation_year = document.getElementById('graduation_year').value;
            const career_id = document.getElementById('career_id').value;
            const gender = document.getElementById('gender').value;

            if (graduation_year) filters.graduation_year = graduation_year;
            if (career_id) filters.career_id = career_id;
            if (gender) filters.gender = gender;

            return filters;
        }

        // Update By Year Chart
        function updateByYearChart(data) {
            const ctx = document.getElementById('byYearChart').getContext('2d');

            if (byYearChart) {
                byYearChart.destroy();
            }

            byYearChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Cantidad de Graduados',
                        data: data.data,
                        backgroundColor: colors.primary,
                        borderColor: colors.primary,
                        borderWidth: 2,
                        borderRadius: 5,
                        hoverBackgroundColor: '#2563eb',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: isDarkMode() ? '#e5e7eb' : '#374151',
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: isDarkMode() ? '#e5e7eb' : '#374151',
                            },
                            grid: {
                                color: isDarkMode() ? '#4b5563' : '#e5e7eb',
                            }
                        },
                        x: {
                            ticks: {
                                color: isDarkMode() ? '#e5e7eb' : '#374151',
                            },
                            grid: {
                                color: isDarkMode() ? '#4b5563' : '#e5e7eb',
                            }
                        }
                    }
                }
            });
        }

        // Update By Gender Chart
        function updateByGenderChart(data) {
            const ctx = document.getElementById('byGenderChart').getContext('2d');

            if (byGenderChart) {
                byGenderChart.destroy();
            }

            byGenderChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: [colors.primary, colors.pink],
                        borderColor: isDarkMode() ? '#374151' : '#ffffff',
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: isDarkMode() ? '#e5e7eb' : '#374151',
                                padding: 20,
                                font: {
                                    size: 14,
                                }
                            }
                        }
                    }
                }
            });
        }

        // Update By Career Chart
        function updateByCareerChart(data) {
            const ctx = document.getElementById('byCareerChart').getContext('2d');

            if (byCareerChart) {
                byCareerChart.destroy();
            }

            byCareerChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Cantidad de Graduados',
                        data: data.data,
                        backgroundColor: chartColors.slice(0, data.data.length),
                        borderRadius: 5,
                        borderSkipped: false,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: isDarkMode() ? '#e5e7eb' : '#374151',
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: isDarkMode() ? '#e5e7eb' : '#374151',
                            },
                            grid: {
                                color: isDarkMode() ? '#4b5563' : '#e5e7eb',
                            }
                        },
                        y: {
                            ticks: {
                                color: isDarkMode() ? '#e5e7eb' : '#374151',
                            },
                            grid: {
                                color: isDarkMode() ? '#4b5563' : '#e5e7eb',
                            }
                        }
                    }
                }
            });
        }

        // Check if dark mode is enabled
        function isDarkMode() {
            return document.documentElement.classList.contains('dark');
        }

        // Submit PDF form with current filters
        function submitPdfForm() {
            const form = document.getElementById('pdfForm');
            const filters = getFilterValues();
            
            // Add filter parameters to form
            for (const [key, value] of Object.entries(filters)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
            
            form.submit();
        }

        // Navigate to email form with current filters
        function goToEmailWithFilters(event) {
            event.preventDefault();
            const filters = getFilterValues();
            const params = new URLSearchParams(filters).toString();
            window.location.href = '{{ route("reports.email-form") }}?' + params;
        }

        // Event listeners for filters
        document.getElementById('graduation_year').addEventListener('change', updateCharts);
        document.getElementById('career_id').addEventListener('change', updateCharts);
        document.getElementById('gender').addEventListener('change', updateCharts);

        // Re-render charts when dark mode changes
        const observer = new MutationObserver(() => {
            updateCharts();
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Initial load
        updateCharts();
    </script>
@endsection
