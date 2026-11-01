<?php

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
