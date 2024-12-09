<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\KelasOlahragaController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PtController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
    Route::resource('alat', AlatController::class);
    Route::resource('user', UserController::class);
    Route::resource('kelas_olahraga', KelasOlahragaController::class);
    Route::resource('layanan', LayananController::class);
    Route::resource('personal_trainer', PtController::class);
    Route::resource('review', ReviewController::class);

})->middleware('auth:sanctum');
// Route::resource('user', UserController::class);


