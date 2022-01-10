<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\BlogController::class, 'home']);

Route::get('/login', [App\Http\Controllers\BlogController::class, 'redirectToLogin'])->name('login');

Route::get('/login/redirect', [App\Http\Controllers\BlogController::class, 'handleLogin']);

Route::get('/post/new', function () {
    return view('post-form');
})->name('post.new.form')->middleware('auth');

Route::post('/post/new', [App\Http\Controllers\BlogController::class, 'savePost'])->name('post.new')->middleware('auth');