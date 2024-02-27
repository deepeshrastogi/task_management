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
        });
    });

    Route::group(['middleware' => 'auth:sanctum','prefix' => 'task','as' => 'task.'], function () {
        Route::post('/', [TaskController::class, 'index'])->name('list');
        Route::get('/{id}', [TaskController::class, 'show'])->name('show');
        Route::delete('/{id}', [TaskController::class, 'destroy'])->name('delete');
        Route::post('create', [TaskController::class, 'store'])->name('create');
        Route::patch('update-status', [TaskController::class, 'updateTaskStatus'])->name('updateTaskStatus');
        Route::get('list/name', [TaskController::class, 'getTaskNameList'])->name('getTaskNameList');
        Route::post('/trashed', [TaskController::class, 'trashedTasks'])->name('trashed.list');
        Route::post('sub-task/create', [SubTaskController::class, 'store'])->name('subtask.create');
    });
});
