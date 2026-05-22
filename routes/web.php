<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GoogleController;

Route::get('/', function () {
    return view('welcome');
});

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Tasks Management
    Route::get('/team-tasks', [TaskController::class, 'teamTasks'])->name('tasks.team');
    Route::post('/tasks/{id}/attachments', [TaskController::class, 'uploadAttachment'])->name('tasks.attachments.upload');
    Route::get('/attachments/{id}', [TaskController::class, 'downloadAttachment'])->name('attachments.download');
    Route::resource('tasks', TaskController::class);

    // Projects Management
    Route::resource('projects', ProjectController::class);

    // Calendar & Deadlines
    Route::get('/calendar', CalendarController::class)->name('calendar');

    // Progress Reports (Managers & Admins only)
    Route::get('/reports', ReportController::class)
        ->middleware('role:manager,admin')
        ->name('reports');
});