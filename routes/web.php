<?php

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

Route::get('/login', function () {
    return view('user/login');
})->name('user.login');

Route::get('/signup', function () {
    return view('user/signup');
})->name('user.signup');

Route::get('/', function () {
    return view('user/dashboard');
})->name('user.dashboard');


Route::get('/tasks', function () {
    return view('task/index');
})->name('task.index');

Route::get('/tasks/create', function () {
    return view('task/create');
})->name('task.create');

Route::get('/sub-task/create', function () {
    return view('task/subtask-create');
})->name('task.subtask.create');

Route::get('/tasks/trashed', function () {
    return view('task/trashed');
})->name('task.trashed');

Route::get('/tasks/{id}', function () {
    return view('task/show');
})->name('task.show');

