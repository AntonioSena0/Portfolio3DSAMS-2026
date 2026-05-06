<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, "login"])->name("login");
Route::get('/register', [MainController::class, "register"])->name("register");
Route::get('/home', [MainController::class, "home"])->name("home")->middleware("auth");

Route::post("/register", [UserController::class, "store"])->name("users.register");
Route::post("/login", [UserController::class, "login"])->name("users.login");
Route::post("/logout", [UserController::class, "logout"])->name("users.logout")->middleware("auth");

Route::post("/create-task", [TaskController::class, "store"])->name("tasks.create")->middleware("auth");
Route::patch("/toggle/{task}", [TaskController::class, "toggle"])->name("tasks.toggle")->middleware("auth");

Route::fallback([MainController::class, "fallback"]);
