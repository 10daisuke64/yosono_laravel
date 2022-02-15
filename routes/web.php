<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CategoryController;

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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/post/category/{category}', [CategoryController::class, 'index'])->name('categories');
    
    Route::post('post/{post}/favorites', [FavoriteController::class, 'store'])->name('favorites');
    Route::post('post/{post}/unfavorites', [FavoriteController::class, 'destroy'])->name('unfavorites');
  
    Route::get('/post/mypage', [PostController::class, 'mydata'])->name('post.mypage');
    Route::resource('post', PostController::class);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
