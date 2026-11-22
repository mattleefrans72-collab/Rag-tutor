<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/Welcome', 'welcome')->name('home');

if (app()->environment('local')) {
    Route::inertia('/ui/preview/dashboard', 'dashboard');
    Route::inertia('/ui/preview/chat', 'chat');
}

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('chat', 'chat')->name('chat');
});

require __DIR__.'/settings.php';
