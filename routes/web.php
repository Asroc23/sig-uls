<?php

use App\Http\Controllers\Api\GraduateStatisticsController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\GraduateController;
use App\Http\Controllers\GraduateDashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Main Dashboard Route (graduates dashboard)
Route::get('/dashboard', GraduateDashboardController::class)->middleware('auth')->name('dashboard');

// Language switching
Route::post('/language/{locale}', [LanguageController::class, 'switch'])
    ->where('locale', 'en|es')
    ->name('language.switch');

// Dashboard Data Routes (for charts)
Route::middleware('auth')->prefix('dashboard/data')->group(function () {
    Route::get('/by-year', [GraduateStatisticsController::class, 'byYear'])->name('dashboard.data.by-year');
    Route::get('/by-gender', [GraduateStatisticsController::class, 'byGender'])->name('dashboard.data.by-gender');
    Route::get('/by-career', [GraduateStatisticsController::class, 'byCareer'])->name('dashboard.data.by-career');
    Route::get('/graduates-count', [ReportController::class, 'getGraduatesCount'])->name('dashboard.data.graduates-count');
});

Route::middleware('auth')->group(function () {
    // Careers CRUD
    Route::resource('careers', CareerController::class);

    // Graduates CRUD
    Route::resource('graduates', GraduateController::class);

    // Reports
    Route::get('/reports/download-pdf', [ReportController::class, 'downloadGraduatesPdf'])->name('reports.download-pdf');
    Route::get('/reports/email', [ReportController::class, 'emailForm'])->name('reports.email-form');
    Route::post('/reports/send-email', [ReportController::class, 'sendEmail'])->name('reports.send-email');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
