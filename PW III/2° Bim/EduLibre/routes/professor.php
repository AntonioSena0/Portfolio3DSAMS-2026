<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:professor', 'verified.professor'])
    ->prefix('professor')
    ->name('professor.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Professor\ProfessorDashboardController::class, 'index'])->name('dashboard');

        // Matérias
        Route::resource('/subjects', App\Http\Controllers\Professor\ProfessorSubjectController::class);
        Route::post('/subjects/{subject}/submit', [App\Http\Controllers\Professor\ProfessorSubjectController::class, 'submit'])->name('subjects.submit');

        // Vídeos
        Route::resource('/subjects/{subject}/videos', App\Http\Controllers\Professor\ProfessorVideoController::class);
        Route::post('/subjects/{subject}/videos/reorder', [App\Http\Controllers\Professor\ProfessorVideoController::class, 'reorder'])->name('videos.reorder');

        Route::get('/metrics', [App\Http\Controllers\Professor\ProfessorMetricsController::class, 'index'])->name('metrics');
        Route::get('/profile', [App\Http\Controllers\Professor\ProfessorProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\Professor\ProfessorProfileController::class, 'update'])->name('profile.update');
    });