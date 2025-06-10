<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\CreateChore;
use App\Livewire\ChoreList;
use App\Livewire\EditChore;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';
Route::get('/create-chore', CreateChore::class)
    ->name('create-chore');

Route::get('/chores', ChoreList::class)
    ->name('chores');    

Route::get('/chores/edit/{chore}', EditChore::class)
    ->name('edit-chore');