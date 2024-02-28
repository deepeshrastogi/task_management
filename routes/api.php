<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Server\Users\UserAuthController;
use App\Http\Controllers\Server\Tasks\TaskController;
use App\Http\Controllers\Server\Tasks\SubTaskController;
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
            Route::get('dashboard', [UserAuthController::class, 'dashboard'])->name('dashboard');
        });
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('task/list-name', [TaskController::class, 'getTaskNameList'])->name('task.name.list');
        Route::get('task/trashed', [TaskController::class, 'trashedTasks'])->name('task.trashed.list');
        Route::resource('task', TaskController::class)->except(['create','edit']);
        Route::resource('sub-task', SubTaskController::class)->only(['store']);
    });
    
});
