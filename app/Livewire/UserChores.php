<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Chores;
use App\Models\User;

class UserChores extends Component
{
    public $chores;
    public $isAdult = false;
    public $filter = 'all'; // 'all', 'assigned_to_me', 'assigned_to_children'
    public $familyUsers = [];
    public $childrenIds = [];

    public function mount()
    {
        $user = Auth::user();

        $family = $user->families()->first();

        if (!$family) {
            $this->chores = collect();
            return;
        }

        // Check if user is adult in this family
        $this->isAdult = $user->families()
                             ->where('family_id', $family->id)
                             ->wherePivot('role', 'adult')
                             ->exists();

        // Load family users once (adults and children)
        $this->familyUsers = $family->members()->get();

$this->childrenIds = $family->members()
    ->wherePivot('role', 'child')
    ->pluck('users.id')
    ->toArray();

        $this->loadChores();
    }

    public function updatedFilter()
    {
        $this->loadChores();
    }

    public function loadChores()
    {
        $user = Auth::user();
        $family = $user->families()->first();

        if ($this->isAdult) {
            // Adults can filter chores assigned to:
            if ($this->filter === 'assigned_to_me') {
                $this->chores = Chores::where('family_id', $family->id)
                    ->whereHas('users', fn($q) => $q->where('users.id', $user->id))
                    ->with('users')
                    ->get();
            } elseif ($this->filter === 'assigned_to_children') {
                $this->chores = Chores::where('family_id', $family->id)
                    ->whereHas('users', fn($q) => $q->whereIn('users.id', $this->childrenIds))
                    ->with('users')
                    ->get();
            } else {
                // All chores in the family
                $this->chores = Chores::where('family_id', $family->id)
                    ->with('users')
                    ->get();
            }
        } else {
            // Children only see chores assigned to themselves
            $this->chores = $user->chores()->with('users')->get();
        }
    }

    public function deleteChore($id)
    {
        if (!$this->isAdult) {
            session()->flash('error', 'You do not have permission to delete chores.');
            return;
        }

        $chore = Chores::find($id);
        if ($chore) {
            $chore->delete();
            session()->flash('message', 'Chore deleted successfully.');
            $this->loadChores(); // refresh chores list
        }
    }

    public function render()
    {
        return view('livewire.user-chores', [
            'chores' => $this->chores,
            'isAdult' => $this->isAdult,
            'familyUsers' => $this->familyUsers,
            'filter' => $this->filter,
        ]);
    }
}
