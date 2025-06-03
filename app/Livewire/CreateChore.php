<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chores;
class CreateChore extends Component
{
    public $title = '';
    public $description = '';
    public $points = 0;
    public $due_date = null;
    public $assigned_to = null;
    public $frequency = ''; // Default frequency

    public function save()
    {
        $validated = $this->validate([
            'title' => 'required|min:3',
            'description' => 'nullable|string|max:255',
            'points' => 'required|integer|min:0',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|string|max:255',
            'frequency' => 'nullable|in:daily,weekly,monthly',
        ]);
        Chores::create($validated);

        session()->flash('message', 'Chore created successfully.');
        $this->reset(['title', 'description', 'points', 'due_date', 'assigned_to', 'frequency']);

    }
    public function render()
    {
        return view('livewire.create-chore');
    }
}
