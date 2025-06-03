<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\CreateChore;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/create-chore', CreateChore::class)
    ->name('create-chore');