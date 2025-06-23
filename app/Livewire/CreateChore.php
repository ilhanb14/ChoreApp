<?php

namespace App\Livewire;

use App\Models\Chores;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateChore extends Component
{
    public $title = '';
    public $description = '';
    public $assigned_to = null;
    public $points = 0;
    public $due_date = null;
    public $isRecurring = false;
    public $frequency = '';

    public $familyUsers = [];

    public function mount()
    {
        $user = Auth::user();
        $family = $user->families->first();

        $this->familyUsers = $family
            ? $family->members()->get()
            : collect();

        $this->due_date = Carbon::now()->addDay()->format('Y-m-d');
    }

    public function updatedIsRecurring($value)
    {
        if (!$value) {
            $this->frequency = null;
        }
    }

public function save()
{
    $user = Auth::user();
    $family = $user->families->first();

    if (!$family) {
        session()->flash('message', 'No family found for the user.');
        return;
    }

    $validated = $this->validate([
        'title' => 'required|string|min:3',
        'description' => 'nullable|string',
        'assigned_to' => 'nullable|exists:users,id',
        'points' => 'required|integer|min:0',
        'due_date' => 'nullable|date',
        'isRecurring' => 'boolean',
        'frequency' => 'nullable|in:daily,weekly,monthly',
    ]);

    $task = Chores::create([
        'name' => $validated['title'],
        'description' => $validated['description'],
        'points' => $validated['points'],
        'recurring' => $validated['isRecurring'],
        'frequency' => $validated['isRecurring'] ? $validated['frequency'] : null,
        'deadline' => $validated['due_date'],
        'family_id' => $family->id,
        'created_by' => $user->id,  
    ]);

if ($validated['assigned_to']) {
    $task->users()->attach($validated['assigned_to'], [
        'performed' => null,
        'confirmed' => false,
        'assigned_by' => Auth::id(),
    ]);

    session()->flash('message', 'Chore created and assigned successfully.');
} else {
    session()->flash('message', 'Bonus task created successfully.');
}

    $this->reset(['title', 'description', 'assigned_to', 'points', 'due_date', 'isRecurring', 'frequency']);
}

    public function render()
    {
        return view('livewire.create-chore');
    }
}
