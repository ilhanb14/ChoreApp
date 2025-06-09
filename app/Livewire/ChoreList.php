<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chores;

class ChoreList extends Component
{
    public $chores;

    public function mount()
    {
        $this->chores = Chores::all();
    }

    public function render()
    {
        return view('livewire.chore-list');
    }
}