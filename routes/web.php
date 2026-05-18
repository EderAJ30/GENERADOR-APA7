<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\ReferenciaController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['guest'])->group(function () {
  Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register');
  Route::post('/registro', [RegisterController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::get('/archivo/descargar/{id_archivo}', [ArchivoController::class, 'descargar'])->name('archivo.descargar');
  Route::post('/referencias', [ReferenciaController::class, 'store'])->name('referencias.store');
  Route::put('/referencias/{id}', [ReferenciaController::class, 'update'])->name('referencias.update');
  Route::delete('/referencias/{id}', [ReferenciaController::class, 'destroy'])->name('referencias.destroy');
});

Route::get('/referencias', [ReferenciaController::class, 'index'])->name('referencias.index');
