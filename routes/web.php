<?php

use App\Http\Controllers\AuditoriumController;
use App\Models\Auditorium;
use Illuminate\Support\Facades\Auth;
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

Route::group(['prefix'=>'auditoriums/','as'=>'auditorium.'],function() {
    Route::get('/',[AuditoriumController::class,'index'])->name('index');
    Route::get('/store',[AuditoriumController::class,'create'])->name('create');
    Route::post('/store',[AuditoriumController::class,'store'])->name('store');
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
