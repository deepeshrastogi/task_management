<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Server\Users\UserAuthController;
use App\Http\Controllers\Server\Tasks\TaskController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'api','as' => 'api.'], function () {
    Route::group(['prefix' => 'user','as' => 'user.'], function () {
        Route::post('/login', [UserAuthController::class, 'login'])->name('login');
        Route::post('/signup', [UserAuthController::class, 'signup'])->name('signup');
        Route::group(['middleware' => 'auth:sanctum'], function() {
            Route::get('logout', [UserAuthController::class, 'logout'])->name('logout');
        });
    });

    Route::group(['middleware' => 'auth:sanctum','prefix' => 'task','as' => 'task.'], function () {
        Route::post('/', [TaskController::class, 'index'])->name('list');
        Route::post('create', [TaskController::class, 'store']);
    });
});
