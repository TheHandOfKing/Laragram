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

Route::get('/', [App\Http\Controllers\PostsController::class, 'index']);

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\UsersController::class, 'show'])->name('dashboard')->middleware('userAdminAuth');

Route::get('/dashboard-admin', [App\Http\Controllers\UsersController::class, 'showAdmin'])->name('dashboardAdmin')->middleware('userAdminAuth');

Route::resource('posts', App\Http\Controllers\PostsController::class, ['except' => ['show']]);

//Specific posts recive slugs, for seo purposes

Route::get('/users', [App\Http\Controllers\UsersController::class, 'index'])->name('users');
Route::delete('/users/{id}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('users.delete');
Route::get('/users/{id}/edit', [App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [App\Http\Controllers\UsersController::class, 'update'])->name('users.update');


Route::get('/posts/{slug}', [App\Http\Controllers\PostsController::class, 'show'])->name('posts.show');

// Route::post('/questions/{question}/answers', 'AnswersController@store')->name('answers.store');

Route::resource('posts.comments', App\Http\Controllers\CommentsController::class, ['except' => ['create, show']]);
Route::post('/comments/{comment}/pin', [App\Http\Controllers\PostsController::class])->name('comments.pin');

Route::post('/posts/{post}/favorites', [App\Http\Controllers\FavoritesController::class, 'store'])->name('posts.favorite');
Route::delete('/posts/{post}/favorites', [App\Http\Controllers\FavoritesController::class, 'destroy'])->name('posts.unfavorite');

Route::post('/posts/{post}/like', App\Http\Controllers\LikePostController::class)->name('posts.like');
Route::post('/comments/{comment}/like', App\Http\Controllers\LikeCommentController::class)->name('comments.vote');


//Contact form
Route::get('/contact-form', [App\Http\Controllers\ContactController::class, 'show'])->name('contact');
Route::post('/contact-form', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::get('/about', [App\Http\Controllers\MenuController::class, 'about'])->name('about');
