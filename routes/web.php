<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/auth', [AuthController::class, 'authenticating']);
});

Route::middleware('auth')->group(function(){

    
    Route::get('/profile/{username}', [ProfileController::class, 'profile']);
    Route::put('/profile-update/{username}', [ProfileController::class, 'update']);
    
    
    Route::middleware('only-admin')->group(function(){
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/user-create', [UserController::class, 'create']);
        Route::post('/user-save', [UserController::class, 'store']);
        Route::get('/user-edit/{username}', [UserController::class, 'edit']);
        Route::put('/user-update/{username}', [UserController::class, 'update']);
        Route::get('/user-delete/{username}', [UserController::class, 'destroy']);
        
    });

    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::get('/404', [ErrorController::class, 'index']);
