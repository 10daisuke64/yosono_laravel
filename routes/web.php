<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

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
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/upload', [ProfileController::class, 'upload'])->name('profile.upload');
    Route::post('/profile/{user}/update', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/post/category/{category}', [CategoryController::class, 'index'])->name('categories');
    Route::get('/post/ranking', [PostController::class, 'ranking'])->name('post.ranking');
    Route::get('/post/search', [PostController::class, 'search'])->name('post.search');
    
    Route::post('post/{post}/favorites', [FavoriteController::class, 'store'])->name('favorites');
    Route::post('post/{post}/unfavorites', [FavoriteController::class, 'destroy'])->name('unfavorites');
    Route::post('post/{post}/comments', [CommentController::class, 'store'])->name('comments');
  
    Route::get('/post/mypage', [PostController::class, 'mydata'])->name('post.mypage');
    
    Route::post('/post/upload', [PostController::class, 'upload'])->name('post.upload');
    Route::resource('post', PostController::class);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
