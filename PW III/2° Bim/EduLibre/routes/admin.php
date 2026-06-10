<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

        // Usuários
        Route::get('/users', [App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/approve', [App\Http\Controllers\Admin\AdminUserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/reject', [App\Http\Controllers\Admin\AdminUserController::class, 'reject'])->name('users.reject');
        Route::post('/users/{user}/block', [App\Http\Controllers\Admin\AdminUserController::class, 'block'])->name('users.block');
        Route::post('/users/{user}/unblock', [App\Http\Controllers\Admin\AdminUserController::class, 'unblock'])->name('users.unblock');

        // Matérias
        Route::get('/subjects', [App\Http\Controllers\Admin\AdminSubjectController::class, 'index'])->name('subjects.index');
        Route::get('/subjects/{subject}', [App\Http\Controllers\Admin\AdminSubjectController::class, 'show'])->name('subjects.show');
        Route::post('/subjects/{subject}/approve', [App\Http\Controllers\Admin\AdminSubjectController::class, 'approve'])->name('subjects.approve');
        Route::post('/subjects/{subject}/reject', [App\Http\Controllers\Admin\AdminSubjectController::class, 'reject'])->name('subjects.reject');
        Route::post('/subjects/{subject}/archive', [App\Http\Controllers\Admin\AdminSubjectController::class, 'archive'])->name('subjects.archive');

        // Vídeos
        Route::get('/videos', [App\Http\Controllers\Admin\AdminVideoController::class, 'index'])->name('videos.index');
        Route::delete('/videos/{video}', [App\Http\Controllers\Admin\AdminVideoController::class, 'destroy'])->name('videos.destroy');

        // Relatórios
        Route::get('/reports', [App\Http\Controllers\Admin\AdminReportController::class, 'index'])->name('reports.index');

        // Categorias
        Route::resource('/categories', App\Http\Controllers\Admin\AdminCategoryController::class);
    });