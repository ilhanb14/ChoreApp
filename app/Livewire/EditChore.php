<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Chores;

class EditChore extends Component
{
    public Chores $chore;

    public $title;
    public $description;
    public $points;
    public $assigned_to;
    public $due_date;
    public $frequency;

    public function mount(Chores $chore)
    {
        $this->chore = $chore;

        $this->title = $chore->title;
        $this->description = $chore->description;
        $this->points = $chore->points;
        $this->assigned_to = $chore->assigned_to;
        $this->due_date = $chore->due_date;
        $this->frequency = $chore->frequency;
    }

    public function updateChore()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points' => 'required|integer',
            'assigned_to' => 'nullable|string',
            'due_date' => 'required|date',
            'frequency' => 'nullable|string',
        ]);

        $this->chore->update([
            'title' => $this->title,
            'description' => $this->description,
            'points' => $this->points,
            'assigned_to' => $this->assigned_to,
            'due_date' => $this->due_date,
            'frequency' => $this->frequency,
        ]);

        session()->flash('message', 'Chore updated successfully.');

        return redirect()->route('chores');
    }

    public function render()
    {
        return view('livewire.edit-chore');
    }
}
