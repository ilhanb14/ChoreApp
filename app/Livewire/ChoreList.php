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

    public function deleteChore($choreId)
    {
        $chore = Chores::find($choreId);
        if ($chore) {
            $chore->delete();
            session()->flash('message', 'Chore deleted successfully.');
            $this->chores = Chores::all(); // refresh
        } else {
            session()->flash('error', 'Chore not found.');
        }
    }

    public function editChore($choreId)
    {
        return redirect()->route('edit-chore', ['chore' => $choreId]);
    }

    public function render()
    {
        return view('livewire.chore-list');
    }
}