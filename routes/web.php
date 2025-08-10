<?php

use App\Livewire\Dashboard\Tags;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');



// /Dashboard/ Routes
Route::view('dashboard', 'livewire.dashboard.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('dashboard/tags', Tags::class)
    ->middleware(['auth', 'verified'])
    ->name('tags');    

Route::view('profile', 'livewire.dashboard.profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
