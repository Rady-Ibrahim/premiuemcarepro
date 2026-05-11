<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EntityController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ChatController;


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
                Route::get('settings', [SettingsController::class, 'index'])->name('settings');
                Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
                Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
                Route::get('users/list', [ChatController::class, 'getUsers'])->name('users.list');

                // Chat API Routes for Admin Panel
                Route::prefix('chat/api')->group(function () {
                    Route::get('/conversations', [ChatController::class, 'getConversations']);
                    Route::post('/conversations', [ChatController::class, 'createConversation']);
                    Route::get('/conversations/{id}/messages', [ChatController::class, 'getMessages']);
                    Route::post('/messages', [ChatController::class, 'sendMessage']);
                    Route::post('/upload', [ChatController::class, 'uploadFile']);
                    Route::post('/conversations/{id}/read', [ChatController::class, 'markAsRead']);
                    Route::get('/chat/file/{path}', [ChatController::class, 'getFile'])->where('path', '.*');
                });
            });
        });




