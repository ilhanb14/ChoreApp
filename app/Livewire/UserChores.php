<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chores;


class UserChores extends Component
{
    public $chores;

    public function mount()
    {
        $user = auth()->user();
        $this->chores = Chores::where('assigned_to', $user->id)->get();
    }
    public function render()
    {
        return view('livewire.user-chores');
    }
}
