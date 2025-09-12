<?php

use App\Livewire\Dashboard\Categories;
use App\Livewire\Dashboard\Posts;
use App\Livewire\Dashboard\PostsCreate;
use App\Livewire\Dashboard\PostsEdit;
use App\Livewire\Dashboard\Tags;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');



// ===========DASHBOARD ROUTES==========
Route::view('dashboard', 'livewire.dashboard.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
    // ========== Posts ==========
    Route::get('/posts', Posts::class)->name('posts.index');
    Route::get('/posts/create', PostsCreate::class)->name('posts.create');
    Route::get('/posts/{post}/edit', PostsEdit::class)->name('posts.edit');

    // ========= Tags & Categories ==========
    Route::get('/tags', Tags::class)->name('tags');   
    Route::get('/categories', Categories::class)->name('categories');
});

Route::view('profile', 'livewire.dashboard.profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
