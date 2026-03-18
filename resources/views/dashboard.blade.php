@extends('layouts.app')

@section('title', __('app.dashboard.title'))

@section('header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('app.dashboard.title') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('app.dashboard.subtitle', ['name' => Auth::user()->name]) }}</p>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Graduates -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('app.dashboard.total_graduates') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">1,234</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z" />
                    </svg>
                </div>
            </div>
            <p class="text-green-600 dark:text-green-400 text-sm mt-4">{{ __('app.dashboard.from_last_month') }}</p>
        </div>

        <!-- Active Careers -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('app.dashboard.active_careers') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">48</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10M7 12v10m6-10v10" />
                    </svg>
                </div>
            </div>
            <p class="text-green-600 dark:text-green-400 text-sm mt-4">{{ __('app.dashboard.new_this_week') }}</p>
        </div>

        <!-- Pending Reports -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('app.dashboard.pending_reports') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">23</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <p class="text-red-600 dark:text-red-400 text-sm mt-4">{{ __('app.dashboard.overdue') }}</p>
        </div>

        <!-- Emails Sent -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('app.dashboard.emails_sent') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">567</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-green-600 dark:text-green-400 text-sm mt-4">{{ __('app.dashboard.delivered') }}</p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Activity Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('app.dashboard.recent_activity') }}</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('app.dashboard.new_graduate') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('app.dashboard.hours_ago', ['count' => 2]) }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ __('app.dashboard.new') }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('app.dashboard.generate_report') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('app.dashboard.hours_ago', ['count' => 5]) }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">{{ __('app.dashboard.completed') }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('app.dashboard.create_career') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('app.dashboard.day_ago', ['count' => 1]) }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">{{ __('app.dashboard.updated') }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('app.dashboard.send_email') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('app.dashboard.days_ago', ['count' => 2]) }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">{{ __('app.dashboard.sent') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('app.dashboard.quick_actions') }}</h3>
            <div class="space-y-3">
                <button class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="font-medium">{{ __('app.dashboard.new_graduate') }}</span>
                </button>
                <button class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="font-medium">{{ __('app.dashboard.create_career') }}</span>
                </button>
                <button class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span class="font-medium">{{ __('app.dashboard.generate_report') }}</span>
                </button>
                <button class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">{{ __('app.dashboard.send_email') }}</span>
                </button>
            </div>
        </div>
    </div>
@endsection
