<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chores;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EditChore extends Component
{
    public Chores $chore;

    public $title;
    public $description;
    public $points;
    public $due_date;
    public $isRecurring;
    public $frequency;
    public $assigned_to;

    public $familyUsers = [];

    public function mount(Chores $chore)
    {
        $this->chore = $chore;

        $this->title = $chore->name;
        $this->description = $chore->description;
        $this->points = $chore->points;
        $this->due_date = $chore->deadline;
        $this->isRecurring = $chore->recurring;
        $this->frequency = $chore->frequency;
        $this->assigned_to = optional($chore->users->first())->id;

        $user = Auth::user();
        $family = $user->families->first();

        $this->familyUsers = $family
            ? $family->members()->get()
            : collect();
    }

    public function updateChore()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points' => 'required|integer|min:0',
            'due_date' => 'required|date',
            'isRecurring' => 'boolean',
            'frequency' => 'nullable|in:daily,weekly,monthly',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $this->chore->update([
            'name' => $this->title,
            'description' => $this->description,
            'points' => $this->points,
            'deadline' => $this->due_date,
            'recurring' => $this->isRecurring,
            'frequency' => $this->isRecurring ? $this->frequency : null,
        ]);

        // Re-attach assignment
        $this->chore->users()->sync([
            $this->assigned_to => [
                'performed' => false,
                'confirmed' => false,
                'assigned_by' => Auth::id(),
            ]
        ]);

        session()->flash('message', 'Chore updated successfully.');
        return redirect()->route('user-chores');
    }

    public function render()
    {
        return view('livewire.edit-chore');
    }
}
