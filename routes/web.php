<?php

use App\Http\Controllers\Admin\AuditoriumController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SeatController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\User\VoucherStockController;
use App\Models\Profile;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/booking/{id}',[HomeController::class,'detail'])->name('detail');
Route::post('/momo-payment',[PaymentController::class,'momo_payment'])->name('momo-payment');
Route::get('/momopayment/paymentsuccess', [PaymentController::class, 'handleMoMoReturn']);
Route::get('/vouchers', [PointController::class, 'index'])->name('vouchers');
Route::post('/vouchers',[VoucherController::class,'saveVoucher'])->name('vouchers.save');
Route::get('/showtimes', [HomeController::class, 'getTimeslotsByDate']);
Route::get('/seats', [HomeController::class, 'getSeatsByShowtimeAndAuditorium']);
// admin
Route::prefix('admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/create', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/{id}', [RoleController::class, 'show'])->name('roles.show');
        Route::put('/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/create', [UserController::class, 'store'])->name('users.store');
        Route::get('/current', [UserController::class, 'getCurrentUser'])->name('users.getCurrentUser');
    });
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/create', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('permissions.show');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/delete/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });
    Route::prefix('auditoriums')->group(function () {
        Route::get('/', [AuditoriumController::class, 'index'])->name('auditoriums.index');
        Route::get('/create', [AuditoriumController::class, 'create'])->name('auditoriums.create');
        Route::post('/store', [AuditoriumController::class, 'store'])->name('auditoriums.store');
        Route::get('show/{id}', [AuditoriumController::class, 'show'])->name('auditoriums.show');
        Route::get('/{id}', [AuditoriumController::class, 'edit'])->name('auditoriums.edit');
        Route::put('/{id}', [AuditoriumController::class, 'update'])->name('auditoriums.update');
        Route::delete('/{id}', [AuditoriumController::class, 'destroy'])->name('auditoriums.destroy');
        Route::get('/getTotalSeats/{id}', [AuditoriumController::class, 'getTotalSeats'])->name('auditoriums.getTotalSeats');
        Route::get('/getTotalAvailableSeats/{id}', [AuditoriumController::class, 'getTotalAvailableSeats'])->name('auditoriums.getTotalAvailableSeats');
        Route::get('/getAuditoriumsOfShowtime/{date}/{movie}/{showtime}', [AuditoriumController::class, 'getAuditoriumsOfShowtime'])->name('auditoriums.getAuditoriumsOfShowtime');
    });
    Route::group(['prefix' => 'tickets', 'as' => 'tickets.'], function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/create', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketController::class, 'edit'])->name('edit');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('destroy');
        Route::get('/getTicketsOfSchedule/{movie}/{date}/{auditorium}/{showtime}', [TicketController::class, 'getTicketsOfSchedule'])->name('getTicketsOfSchedule');
    });
    Route::group(['prefix' => 'seats', 'as' => 'seats.'], function () {
        Route::get('/', [SeatController::class, 'index'])->name('index');
        Route::get('/create', [SeatController::class, 'create'])->name('create');
        Route::get('/single-create', [SeatController::class, 'singleCreate'])->name('singleCreate');
        Route::post('/create', [SeatController::class, 'store'])->name('store');
        Route::get('/show/{seat}', [SeatController::class, 'show'])->name('show');
        Route::get('/{seat}', [SeatController::class, 'edit'])->name('edit');
        Route::put('/{seat}', [SeatController::class, 'update'])->name('update');
        Route::delete('/{seat}', [SeatController::class, 'destroy'])->name('destroy');
        Route::get('/getSeatsOfAuditorium/{auditorium}', [SeatController::class, 'getSeatsOfAuditorium'])->name('getSeatsOfAuditorium');
        Route::get('/getSeatNumber/{id}', [SeatController::class, 'getSeatNumber'])->name('getSeatNumber');
        Route::get('/getSeatId/{seat_number}/{auditorium}', [SeatController::class, 'getSeatId'])->name('getSeatId');
    });
    Route::group(['prefix' => 'schedules', 'as' => 'schedules.'], function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::get('/create', [ScheduleController::class, 'create'])->name('create');
        Route::post('/create', [ScheduleController::class, 'store'])->name('store');
        Route::get('/{schedule}', [ScheduleController::class, 'edit'])->name('edit');
        Route::put('/{schedule}', [ScheduleController::class, 'update'])->name('update');
        Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])->name('destroy');
        Route::get('/{schedule}/{showtime}', [ScheduleController::class, 'deleteShowtimes'])->name('deleteShowtimes');
        Route::get('/getSchedule/{movie}/{date}/{auditorium}', [ScheduleController::class, 'getSchedule'])->name('getSchedule');
        Route::get('/getDatesOfMovieAndAuditorium/{movie}/{auditorium}', [ScheduleController::class, 'getDatesOfMovieAndAuditorium'])->name('getDatesOfMovieAndAuditorium');
        Route::get('/getDateOfMovieAndShowtime/{movie}/{showtime}', [ScheduleController::class, 'getDateOfMovieAndShowtime'])->name('getDateOfMovieAndShowtime');
    });
    Route::prefix('movies')->group(function () {
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('movies.categories.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('movies.categories.create');
            Route::post('/create', [CategoryController::class, 'store'])->name('movies.categories.store');
            Route::get('/{id}', [CategoryController::class, 'edit'])->name('movies.categories.edit');
            Route::get('/show/{id}', [CategoryController::class, 'show'])->name('movies.categories.show');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('movies.categories.update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('movies.categories.destroy');
        });
        Route::prefix('features')->group(function () {
            Route::get('/', [MovieController::class, 'index'])->name('movies.features.index');
            Route::get('/create', [MovieController::class, 'create'])->name('movies.features.create');
            Route::get('show/{id}', [MovieController::class, 'show'])->name('movies.features.show');
            Route::post('/upload-images', [MovieController::class, 'uploadImages'])->name('movies.features.uploadImages');
            Route::post('/create', [MovieController::class, 'store'])->name('movies.features.store');
            Route::get('/{id}', [MovieController::class, 'edit'])->name('movies.features.edit');
            Route::put('/{id}', [MovieController::class, 'update'])->name('movies.features.update');
            Route::delete('/{id}', [MovieController::class, 'destroy'])->name('movies.features.destroy');
            Route::get('/getDuration/{id}', [MovieController::class, 'getDuration'])->name('movies.getDuration');
            Route::get('/getDates/{id}', [MovieController::class, 'getDates'])->name('movies.getDates');
            Route::get('/getSchedule/{id}', [MovieController::class, 'getSchedule'])->name('movies.getSchedule');
            Route::get('/getPrice/{id}', [MovieController::class, 'getPrice'])->name('movies.getPrice');
        });
        Route::get('getShowtimes/{id}', [MovieController::class, 'getShowtimes'])->name('movies.getShowtimes');
    });
    Route::group(['prefix' => 'showtimes', 'as' => 'showtimes.'], function () {
        Route::get('/', [ShowtimeController::class, 'index'])->name('index');
        Route::get('/create', [ShowtimeController::class, 'create'])->name('create');
        Route::post('/store', [ShowtimeController::class, 'store'])->name('store');
        Route::get('getSeats/{id}', [ShowtimeController::class, 'getSeats'])->name('getSeats');
        Route::get('/edit/{showtime}', [ShowtimeController::class, 'edit'])->name('edit');
        Route::put('/update/{showtime}', [ShowtimeController::class, 'update'])->name('update');
        Route::delete('/{showtime}', [ShowtimeController::class, 'destroy'])->name('destroy');
        Route::get('/getShowtimesOfDuration/{duration}', [ShowtimeController::class, 'getShowtimesOfDuration'])->name('getShowtimesOfDuration');
        Route::get('/getDullicateShowtimes/{auditoriums}/{date}', [ShowtimeController::class, 'getDullicateShowtimes'])->name('getDullicateShowtimes');
        Route::get('/getAvailableShowtimes/{auditoriums}/{date}/{duration}', [ShowtimeController::class, 'getAvailableShowtimes'])->name('getAvailableShowtimes');
        Route::get('/getAvailableShowtimesOfSchedule/{schedule}/{auditoriums}/{date}/{duration}', [ShowtimeController::class, 'getAvailableShowtimesOfSchedule'])->name('getAvailableShowtimesOfSchedule');
        Route::get('/getShowtimesOfAuditorium/{auditorium}', [ShowtimeController::class, 'getShowtimesOfAuditorium'])->name('getShowtimesOfAuditorium');
        Route::get('/getShowtimesOfMovieAndDate/{date}/{movie}', [ShowtimeController::class, 'getShowtimesOfMovieAndDate'])->name('getShowtimesOfAuditoriumAndDate');
        Route::get('/getShowtimeOfSchedule/{schedule}', [ShowtimeController::class, 'getShowtimeOfSchedule'])->name('getShowtimeOfSchedule');
    });
    Route::get('/', [DashboardController::class, 'index'])->middleware('permissions')->name('dashboards.index');

    Route::get('/customers', [CustomerController::class, 'index'])->middleware('permissions')->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->middleware('permissions')->name('customers.create');
    Route::post('/customers/create', [CustomerController::class, 'store'])->middleware('permissions')->name('customers.store');
    Route::get('/customers/{id}', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/delete/{id}', [CustomerController::class, 'destroy'])->middleware('permissions')->name('customers.destroy');
    Route::get('/getCustomerInfor/{id}', [CustomerController::class, 'getCustomerInfor'])->name('customers.getCustomerInfor');

    Route::prefix('vouchers')->group(function () {
        Route::get('/', [VoucherController::class, 'index'])->name('vouchers.index');
        Route::get('/create', [VoucherController::class, 'create'])->name('vouchers.create');
        Route::post('/create', [VoucherController::class, 'store'])->name('vouchers.store');
        Route::get('/edit/{id}', [VoucherController::class, 'edit'])->name('vouchers.edit');
        Route::put('/edit/{id}', [VoucherController::class, 'update'])->name('vouchers.update');
        Route::delete('/delete/{id}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
    });
});

Route::prefix('users')->group(function () {
    Route::prefix('/stock-voucher')->group(function () {
        Route::get('/',[VoucherStockController::class, 'index'])->name('voucher_stock.index');
    });
});

