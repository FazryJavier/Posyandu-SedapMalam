<?php

use App\Http\Controllers\FamilyProgressController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Only the public landing page lives here. The admin panel (login, RBAC,
| residents, growth tracking and CMS) is served by Filament at /admin.
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Private family progress page: the unguessable token in the URL is the only credential.
Route::get('/keluarga/{token}', [FamilyProgressController::class, 'show'])
    ->where('token', '[A-Za-z0-9]{40}')
    ->middleware('throttle:family-link')
    ->name('family.show');
