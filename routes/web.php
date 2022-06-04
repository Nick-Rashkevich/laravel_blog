<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group([ 'name' => 'auth'], function (){
    Route::get('register', [AuthController::class, 'registerForm'])->name('register');
    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', [AuthController::class, 'check'])->name('profile');
});


Route::group(['prefix' => 'admin'], function(){
    Route::get('/', [DashboardController::class, 'index']);

    Route::resource('categories',CategoryController::class)
        ->parameters([
            'categories' => 'category:slug'
        ]);
    Route::resource('tags', TagController::class)
        ->parameters([
            'tags' => 'tag:slug'
        ]);
    Route::resource('users', UserController::class);

    Route::resource('posts', PostController::class);
});

//Route::get('/',);