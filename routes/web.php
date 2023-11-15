<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
        MicrosoftController
};
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReflectionController;
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


Route::controller(MicrosoftController::class)->group(function () {
        Route::get('login', 'login')->name('login');
        Route::get('logout', 'logout')->name('logout');
});


Route::name('materials.')->controller(MaterialController::class)->group(function () {
        Route::get('materials', 'create')->name('create');
        Route::post('materials', 'store')->name('store');
        Route::delete('materials', 'forget')->name('forget');
})->middleware(Authenticate::class);

Route::resource('projects', ProjectController::class)->middleware(Authenticate::class);

Route::name('evaluations.')->controller(EvaluationController::class)->group(function () {
        Route::get('evaluations', 'create')->name('create');
        Route::post('evaluations', 'store')->name('store');
        Route::delete('evaluations', 'reset')->name('reset');
})->middleware(Authenticate::class);

Route::get('reflection', ReflectionController::class)->middleware(Authenticate::class)->name('reflection');
