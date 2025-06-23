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
    public $userFamilies = [];
    public $selectedFamilyId;

    public function mount()
    {
        $user = Auth::user();
        $this->userFamilies = $user->families()->get();
        $this->selectedFamilyId = session('selected_family_id', $this->userFamilies->first()?->id);

        $this->loadFamilyUsers();
        $this->due_date = Carbon::now()->addDay()->format('Y-m-d');
    }

    public function changeFamily($familyId)
    {
        $this->selectedFamilyId = $familyId;
        session(['selected_family_id' => $familyId]);
        $this->loadFamilyUsers();
    }

    public function loadFamilyUsers()
    {
        $this->familyUsers = Auth::user()
            ->families()
            ->where('family_id', $this->selectedFamilyId)
            ->first()
            ?->members()
            ->get() ?? collect();
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
            'family_id' => $this->selectedFamilyId,
            'created_by' => $user->id,
        ]);

        if ($validated['assigned_to']) {
            $task->users()->attach($validated['assigned_to'], [
                'performed' => null,
                'confirmed' => false,
                'assigned_by' => $user->id,
            ]);

            session()->flash('message', 'Chore created and assigned successfully.');
        } else {
            session()->flash('message', 'Bonus task created successfully.');
        }

        $this->reset([
            'title', 'description', 'assigned_to', 'points', 'due_date',
            'isRecurring', 'frequency'
        ]);
    }

    public function render()
    {
        return view('livewire.create-chore');
    }
}
