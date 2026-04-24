<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::livewire('/login', 'pages::login')->name('login')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::livewire('/', 'pages::task-index')->name('tasks.index');
    Route::livewire('/tasks/create', 'pages::task-create')->name('tasks.create');
    Route::livewire('/tasks/{task}/edit', 'pages::task-edit')->name('tasks.edit');
    Route::livewire('/tasks/{task}', 'pages::task-view')->name('tasks.show');
    Route::livewire('/calendar', 'pages::calendar')->name('calendar');
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

