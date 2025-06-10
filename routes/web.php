<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\CreateChore;
use App\Http\Controllers\RewardsController;


Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

Route::get('/create-chore', CreateChore::class)
    ->name('create-chore');

Route::get('/rewards', [RewardsController::class, 'getRewardsView'])
    ->name('rewards');
Route::post('/claim-reward', [RewardsController::class, 'claimReward'])
    ->name('claim-reward');
Route::post('/remove-reward', [RewardsController::class, 'removeReward'])
    ->name('remove-reward');