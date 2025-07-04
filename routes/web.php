<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\CreateChore;
use App\Http\Controllers\RewardsController;
use App\Livewire\ChoreList;
use App\Livewire\EditChore;
use App\Livewire\UserChores;

use App\Http\Controllers\FamilyController;
use App\Http\Controllers\InviteController;
use App\Livewire\TasksCalendar;
use App\Livewire\UserProfile;

Route::get('/', function () {
    $user = auth()->user();
    if ($user) {
        $recommendedChore = $user->chores()
            ->wherePivotNull('performed')
            ->orderBy('deadline')
            ->first();

        return view('welcome', ['recommendedChore' => $recommendedChore]);
    }

    return view('welcome');
});

// Auth routes
require __DIR__.'/auth.php';

// Route::middleware(['auth'])->group(function () {

Route::middleware(['auth'])->group(function () {

    // Family routes
    Route::post('/families', [FamilyController::class, 'create'])->name('families.create');
    Route::post('/families/{family}/invites', [FamilyController::class, 'sendInvite'])->name('families.invites.send');

    // Profile routes
    Route::get('/profile', UserProfile::class)
        ->name('profile');
    
    // Invitation routes
    Route::post('/invites/{invite}/accept', [InviteController::class, 'accept'])->name('invite.accept');
    Route::post('/invites/{invite}/decline', [InviteController::class, 'decline'])->name('invite.decline');

    // Chore routes
    Route::get('/create-chore', CreateChore::class)
        ->name('create-chore');
    Route::get('/chores', CreateChore::class)
        ->name('chores');    
    Route::get('/chores/edit/{chore}', EditChore::class)
        ->name('edit-chore');
    Route::get('/my-chores', UserChores::class)
    ->name('user-chores');

    // Reward routes
    Route::get('/rewards', [RewardsController::class, 'getRewardsView'])
        ->name('rewards');
    Route::post('/claim-reward', [RewardsController::class, 'claimReward'])
        ->name('claim-reward');
    Route::post('/remove-reward', [RewardsController::class, 'removeReward'])
        ->name('remove-reward');

    // Calendar
    Route::get('/calendar', function () {
        return view('livewire.tasks-calendar');
    });
});