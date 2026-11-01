<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\CadreController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WeighingController;
use App\Http\Controllers\GrowthChartController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index']);

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
    Route::get('/data-orangtua', [GuardianController::class, 'index']);
    Route::post('/data-orangtua', [GuardianController::class, 'store']);
    Route::get('/data-orangtua/{id}/update', [GuardianController::class, 'edit']);
    Route::put('/data-orangtua/{id}', [GuardianController::class, 'update']);
    Route::delete('/data-orangtua/{id}', [GuardianController::class, 'destroy']);

    // Data Anak
    Route::get('/data-anak', [ChildController::class, 'index']);
    Route::post('/data-anak', [ChildController::class, 'store']);
    Route::get('/data-anak/{id}/update', [ChildController::class, 'edit']);
    Route::put('/data-anak/{id}', [ChildController::class, 'update']);
    Route::delete('/data-anak/{id}', [ChildController::class, 'destroy']);

    // Data Kader
    Route::get('/data-kader', [CadreController::class, 'index']);
    Route::post('/data-kader', [CadreController::class, 'store']);
    Route::get('/data-kader/{id}/update', [CadreController::class, 'edit']);
    Route::put('/data-kader/{id}', [CadreController::class, 'update']);
    Route::delete('/data-kader/{id}', [CadreController::class, 'destroy']);

    // Timbang Anak
    Route::get('/timbang-anak', [WeighingController::class, 'index']);
    Route::post('/timbang-anak', [WeighingController::class, 'store']);
    Route::get('/timbang-anak/{id}/update', [WeighingController::class, 'edit']);
    Route::put('/timbang-anak/{id}', [WeighingController::class, 'update']);
    Route::delete('/timbang-anak/{id}', [WeighingController::class, 'destroy']);

    // Grafik Perkembangan
    Route::get('/grafik-perkembangan', [GrowthChartController::class, 'index']);
});
