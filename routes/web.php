<?php

use App\Http\Controllers\Api\GraduateStatisticsController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\EventController;
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
    Route::get('/events-by-year', [EventController::class, 'getEventsByYear'])->name('dashboard.data.events-by-year');
    Route::get('/participants-by-event', [EventController::class, 'getParticipantsByEvent'])->name('dashboard.data.participants-by-event');
});

Route::middleware('auth')->group(function () {
    // Careers CRUD
    Route::resource('careers', CareerController::class);

    // Graduates CRUD
    Route::resource('graduates', GraduateController::class);

    // Events CRUD and Attendance
    Route::get('/events/search-graduates', [EventController::class, 'searchGraduates'])->name('events.searchGraduates');
    Route::resource('events', EventController::class);
    Route::post('/events/{event}/attach-graduate', [EventController::class, 'attachGraduate'])->name('events.attachGraduate');
    Route::post('/events/{event}/detach-graduate', [EventController::class, 'detachGraduate'])->name('events.detachGraduate');
    Route::get('/events/{event}/pdf', [EventController::class, 'downloadPdf'])->name('events.downloadPdf');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');
    Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
