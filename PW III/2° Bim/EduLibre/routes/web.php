<?php

use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\SubjectController;
use App\Http\Controllers\Public\TeacherController;
use App\Http\Controllers\Public\VideoController;
require __DIR__ . '/auth.php';
require __DIR__ . '/student.php';
require __DIR__ . '/professor.php';
require __DIR__ . '/admin.php';

// Rotas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [SubjectController::class, 'show'])->name('subjects.show');
Route::get('/catalog/{subjectSlug}/videos/{videoSlug}', [VideoController::class, 'show'])->name('videos.show');
Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
