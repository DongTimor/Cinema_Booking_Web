<?php

use App\Http\Controllers\Admin\AuditoriumController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SeatController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\TicketController;
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


    Route::prefix('movies')->group(function() {
        Route::prefix('categories')->group(function(){
            Route::get('/',[CategoryController::class,'index'])->name('movies.categories.index');
            Route::get('/create',[CategoryController::class,'create'])->name('movies.categories.create');
            Route::post('/create',[CategoryController::class,'store'])->name('movies.categories.store');
            Route::get('/{id}',[CategoryController::class,'edit'])->name('movies.categories.edit');
            Route::put('/{id}',[CategoryController::class,'update'])->name('movies.categories.update');
            Route::delete('/{id}',[CategoryController::class,'destroy'])->name('movies.categories.destroy');
        });
        Route::prefix('features')->group(function(){
            Route::get('/',[MovieController::class,'index'])->name('movies.features.index');
            Route::get('/create',[MovieController::class,'create'])->name('movies.features.create');
            Route::post('/create',[MovieController::class,'store'])->name('movies.features.store');
            Route::get('/{id}',[MovieController::class,'edit'])->name('movies.features.edit');
            Route::put('/{id}',[MovieController::class,'update'])->name('movies.features.update');
            Route::delete('/{id}',[MovieController::class,'destroy'])->name('movies.features.destroy');
        });
        Route::get('getShowtimes/{id}',[MovieController::class, 'getShowtimes'])->name('movies.getShowtimes');
      
    });
    Route::group(['prefix'=>'showtimes', 'as'=>'showtimes.'], function(){
        Route::get('/',[ShowtimeController::class, 'index'])->name('index');
        Route::get('/create',[ShowtimeController::class, 'create'])->name('create');
        Route::post('/store',[ShowtimeController::class, 'store'])->name('store');
        Route::get('getSeats/{id}',[ShowtimeController::class, 'getSeats'])->name('getSeats');
        Route::get('/edit/{showtime}',[ShowtimeController::class, 'edit'])->name('edit');
        Route::put('/update/{showtime}',[ShowtimeController::class, 'update'])->name('update');
        Route::delete('/delete/{showtime}',[ShowtimeController::class, 'destroy'])->name('destroy');
    });
});

