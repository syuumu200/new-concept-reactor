<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
        MicrosoftController
};
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProjectController;
use App\Http\Middleware\Authenticate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::inertia('/', 'Welcome')->name('index');

Route::get('login', [MicrosoftController::class, 'login'])->name('login');
Route::get('logout', [MicrosoftController::class, 'logout'])->name('logout');

Route::resource('projects', ProjectController::class)->middleware(Authenticate::class);;
Route::resource('materials', MaterialController::class)->middleware(Authenticate::class);;
