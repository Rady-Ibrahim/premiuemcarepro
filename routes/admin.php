<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EntityController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController;


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


        Route::prefix('admin')->group(function () {


            Route::view('login', 'admin.login')->middleware('admin_guest')->name('login');
            Route::post('login', [AuthController::class, 'login'])->middleware('admin_guest')->name('login.post');


            Route::group(['middleware' => 'admin'], function () {
                Route::post('logout', [AuthController::class, 'logout'])->name('logout');
                Route::get('/', [DashboardController::class, 'index'])->name('home');
                Route::resource('entities', EntityController::class);
                Route::resource('customers', CustomerController::class);
                Route::resource('providers', ProviderController::class);
                Route::resource('tickets', TicketController::class);
                Route::post('tickets/storeComment', [TicketController::class, 'storeComment'])->name('tickets.storeComment');
                Route::view('settings', 'admin.settings')->name('settings');
                Route::post('settings', [SettingsController::class, 'settings'])->name('settings.update');
            });
        });




