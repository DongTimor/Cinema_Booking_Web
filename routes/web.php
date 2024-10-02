<?php

use App\Http\Controllers\Admin\AuditoriumController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TicketController;
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


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('admin')->group(function () {
    Route::prefix('roles')->group(function() {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/create', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/{id}', [RoleController::class, 'show'])->name('roles.show');
        Route::put('/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::post('/delete/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });
    Route::prefix('permissions')->group(function() {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/create', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('permissions.show');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::post('/delete/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });
    Route::prefix('auditoriums')->group(function() {
        Route::get('/',[AuditoriumController::class,'index'])->name('auditoriums.index');
        Route::get('/create',[AuditoriumController::class,'create'])->name('auditoriums.create');
        Route::post('/store',[AuditoriumController::class,'store'])->name('auditoriums.store');
        Route::get('/{id}',[AuditoriumController::class,'edit'])->name('auditoriums.edit');
        Route::put('/{id}',[AuditoriumController::class,'update'])->name('auditoriums.update');
        Route::delete('/{id}',[AuditoriumController::class,'destroy'])->name('auditoriums.destroy');
    });
    Route::group(['prefix'=>'tickets', 'as'=>'tickets.'], function(){
        Route::get('/',[TicketController::class, 'index'])->name('index');
        Route::get('/edit/{ticket}',[TicketController::class, 'edit'])->name('edit');
        Route::put('/update/{ticket}',[TicketController::class, 'update'])->name('update');
    });

    Route::group(['prefix'=>'seats', 'as'=>'seats.'], function(){
        Route::get('/',[SeatController::class, 'index'])->name('index');
    });

    Route::group(['prefix'=>'movies', 'as'=>'movies.'], function(){
        Route::get('getShowtimes/{id}',[MovieController::class, 'getShowtimes'])->name('getShowtimes');
    });

    Route::group(['prefix'=>'showtimes', 'as'=>'showtimes.'], function(){
        Route::get('getSeats/{id}',[ShowtimeController::class, 'getSeats'])->name('getSeats');
    });
});



