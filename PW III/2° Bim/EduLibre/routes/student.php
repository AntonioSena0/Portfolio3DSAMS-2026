<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student,professor,admin'])->prefix('dashboard')->name('student.')->group(function () {
    Route::get('/', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-subjects', [App\Http\Controllers\Student\EnrollmentController::class, 'index'])->name('subjects');
    Route::get('/history', [App\Http\Controllers\Student\ProgressController::class, 'history'])->name('history');
    Route::get('/continue-watching', [App\Http\Controllers\Student\ProgressController::class, 'continueWatching'])->name('continue');
    Route::get('/profile', [App\Http\Controllers\Student\ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [App\Http\Controllers\Student\ProfileController::class, 'settings'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\Student\ProfileController::class, 'updateSettings'])->name('settings.update');
    Route::get('/ratings', [App\Http\Controllers\Student\RatingController::class, 'index'])->name('ratings');
    Route::get('/comments', [App\Http\Controllers\Student\CommentController::class, 'index'])->name('comments');

    // AJAX
    Route::post('/progress', [App\Http\Controllers\Student\ProgressController::class, 'save'])->name('progress.save');
    Route::post('/videos/{video}/rate', [App\Http\Controllers\Student\RatingController::class, 'store'])->name('ratings.store');
    Route::post('/videos/{video}/comment', [App\Http\Controllers\Student\CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [App\Http\Controllers\Student\CommentController::class, 'destroy'])->name('comments.destroy');
});