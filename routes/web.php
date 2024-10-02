<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
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

Route::get('/', function () {
    return view('welcome');
});

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
});



