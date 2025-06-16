<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\CreateChore;
use App\Http\Controllers\RewardsController;
use App\Livewire\ChoreList;

use App\Http\Controllers\FamilyController;
use App\Http\Controllers\InviteController;
use App\Livewire\TasksCalendar;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

Route::get('/create-chore', CreateChore::class)
    ->name('create-chore');
Route::get('/chores', ChoreList::class)
    ->name('chores');    

// Route::middleware(['auth'])->group(function () {
    // Family routes
    Route::post('/families', [FamilyController::class, 'create'])->name('families.create');
    Route::post('/families/{family}/invites', [FamilyController::class, 'sendInvite'])->name('families.invites.send');
    
    // Invitation routes
    Route::get('/invites', [InviteController::class, 'index'])->name('invites.index');
    Route::post('/invites/{invite}/accept', [InviteController::class, 'accept'])->name('invite.accept');
    Route::post('/invites/{invite}/decline', [InviteController::class, 'decline'])->name('invite.decline');
// });
 

Route::get('/rewards', [RewardsController::class, 'getRewardsView'])
    ->name('rewards');
Route::post('/claim-reward', [RewardsController::class, 'claimReward'])
    ->name('claim-reward');
Route::post('/remove-reward', [RewardsController::class, 'removeReward'])
    ->name('remove-reward');

Route::get('/calendar', function () {
    return view('livewire.tasks-calendar');
});