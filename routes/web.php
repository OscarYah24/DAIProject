<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;

// Ruta principal del blog
Route::get('/', [BlogController::class, 'index'])->name('blog.index');

// Rutas de autenticación
Auth::routes();

// Ruta del dashboard
Route::get('/home', [HomeController::class, 'index'])->name('home');
