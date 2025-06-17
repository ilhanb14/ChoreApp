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
            $this->chores = Chores::all(); // Refresh the list
        } else {
            session()->flash('error', 'Chore not found.');
        }
    }
    public function editChore($choreId)
    {
        $chore = Chores::find($choreId);
        if ($chore) {
            // Logic to edit the chore, e.g., redirect to an edit form
            return redirect()->route('edit-chore', ['chore' => $choreId]);
        } else {
            session()->flash('error', 'Chore not found.');
        }
    }


    public function render()
    {
        return view('livewire.chore-list');
    }
}