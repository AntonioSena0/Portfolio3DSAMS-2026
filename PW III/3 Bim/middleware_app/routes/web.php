<?php

use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortalController::class, 'index']);
Route::get('/portal', [PortalController::class, 'index'])->name('portal');
