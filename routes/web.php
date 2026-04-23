<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::task-index')->name('tasks.index');
Route::livewire('/tasks/create', 'pages::task-create')->name('tasks.create');
Route::livewire('/tasks/{task}/edit', 'pages::task-edit')->name('tasks.edit');
Route::livewire('/tasks/{task}', 'pages::task-view')->name('tasks.show');
Route::livewire('/calendar', 'pages::calendar')->name('calendar');
