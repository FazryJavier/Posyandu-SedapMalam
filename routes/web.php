<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAnakController;
use App\Http\Controllers\DataKaderController;
use App\Http\Controllers\DataOrangtuaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TimbangAnakController;
use App\Http\Controllers\GrafikPerkembanganController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('User.home');
});

// Login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

// Registrasi
Route::get('/admin-registrasi-only', [RegistrationController::class, 'index']);
Route::post('/admin-registrasi-only', [RegistrationController::class, 'store']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Data Orangtua
    Route::get('/data-orangtua', [DataOrangtuaController::class, 'index']);
    Route::post('/data-orangtua', [DataOrangtuaController::class, 'store']);
    Route::get('/data-orangtua/{id}/update', [DataOrangtuaController::class, 'edit']);
    Route::put('/data-orangtua/{id}', [DataOrangtuaController::class, 'update']);
    Route::delete('/data-orangtua/{id}', [DataOrangtuaController::class, 'destroy']);

    // Data Anak
    Route::get('/data-anak', [DataAnakController::class, 'index']);
    Route::post('/data-anak', [DataAnakController::class, 'store']);
    Route::get('/data-anak/{id}/update', [DataAnakController::class, 'edit']);
    Route::put('/data-anak/{id}', [DataAnakController::class, 'update']);
    Route::delete('/data-anak/{id}', [DataAnakController::class, 'destroy']);

    // Data Kader
    Route::get('/data-kader', [DataKaderController::class, 'index']);
    Route::post('/data-kader', [DataKaderController::class, 'store']);
    Route::get('/data-kader/{id}/update', [DataKaderController::class, 'edit']);
    Route::put('/data-kader/{id}', [DataKaderController::class, 'update']);
    Route::delete('/data-kader/{id}', [DataKaderController::class, 'destroy']);

    // Timbang Anak
    Route::get('/timbang-anak', [TimbangAnakController::class, 'index']);
    Route::post('/timbang-anak', [TimbangAnakController::class, 'store']);
    Route::get('/timbang-anak/{id}/update', [TimbangAnakController::class, 'edit']);
    Route::put('/timbang-anak/{id}', [TimbangAnakController::class, 'update']);
    Route::delete('/timbang-anak/{id}', [TimbangAnakController::class, 'destroy']);

    // Grafik Perkembangan
    Route::get('/grafik-perkembangan', [GrafikPerkembanganController::class, 'index']);
});
