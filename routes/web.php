<?php

use App\Http\Controllers\BasicController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('', [BasicController::class, 'welcome'])->name('welcome');
Route::get('/work', [BasicController::class, 'work'])->name('work');
Route::get('/me', [BasicController::class, 'me'])->name('me');
Route::get('/contacts', [BasicController::class, 'contacts'])->name('contacts');

Route::get('/work/create', [PostController::class, 'create'])->name('posts.create');
Route::get('/work/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/work/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::post('/work', [PostController::class, 'store'])->name('posts.store');
Route::put('/work/{post}', [PostController::class, 'update'])->name('posts.update');
Route::get('/work/{post}/delete', [PostController::class, 'delete'])->name('posts.delete');
Route::delete('/work/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
