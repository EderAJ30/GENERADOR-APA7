<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReferenciaController;

Route::get('/', function () {
  return redirect()->route('referencias.index');
});

Route::get('/referencias', [ReferenciaController::class, 'index'])->name('referencias.index');
Route::post('/referencias', [ReferenciaController::class, 'store'])->name('referencias.store');
Route::delete('/referencias/{id}', [ReferenciaController::class, 'destroy'])->name('referencias.destroy');
Route::put('/referencias/{id}', [ReferenciaController::class, 'update'])->name('referencias.update');
