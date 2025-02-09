<?php

use App\Http\Controllers\BasicController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('', [BasicController::class, 'welcome'])->name('welcome');
Route::get('/work', [BasicController::class, 'work'])->name('work');
Route::get('/me', [BasicController::class, 'me'])->name('me');
Route::get('/contacts', [BasicController::class, 'contacts'])->name('contacts');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// protected routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/work/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/work', [PostController::class, 'store'])->name('posts.store');
    Route::get('/work/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/work/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::get('/work/{post}/delete', [PostController::class, 'delete'])->name('posts.delete');
    Route::delete('/work/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});

Route::get('/work/{post}', [PostController::class, 'show'])->name('posts.show');

// only for authenticated users
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
