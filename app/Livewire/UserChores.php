<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Chores;
use Illuminate\Support\Facades\DB;

class UserChores extends Component
{
    public $chores;
    public $completedChores = [];
    public $isAdult = false;
    public $filter = 'assigned_to_me';
    public $familyUsers = [];
    public $childrenIds = [];
    public $totalPoints = 0;
    public $pendingConfirmations = [];
    public $bonusTasks = [];

    public $userFamilies = [];
    public $selectedFamilyId;

    public function mount()
    {
        $user = Auth::user();
        $this->userFamilies = $user->families()->get();

        // Use session value if available, otherwise default to first family
        $this->selectedFamilyId = session('selected_family_id', $this->userFamilies->first()?->id);

        if (!$this->selectedFamilyId) {
            $this->chores = collect();
            $this->completedChores = collect();
            $this->bonusTasks = collect();
            return;
        }

        $this->isAdult = $user->families()
            ->where('family_id', $this->selectedFamilyId)
            ->wherePivot('role', 'adult')
            ->exists();

        $family = $user->families()->where('family_id', $this->selectedFamilyId)->first();

        if ($family) {
            $this->familyUsers = $family->members()->get();
            $this->childrenIds = $family->members()
                ->wherePivot('role', 'child')
                ->pluck('users.id')
                ->toArray();
        } else {
            $this->familyUsers = [];
            $this->childrenIds = [];
        }

        $this->loadChores();
        $this->loadPointsAndCompletions($this->selectedFamilyId, $user->id);
        $this->loadBonusTasks();
    }

    public function changeFamily($familyId)
    {
        $this->selectedFamilyId = $familyId;

        // Save selection to session for persistence across refreshes
        session(['selected_family_id' => $familyId]);

        $user = Auth::user();
        $this->isAdult = $user->families()
            ->where('family_id', $this->selectedFamilyId)
            ->wherePivot('role', 'adult')
            ->exists();

        $family = $user->families()->where('family_id', $this->selectedFamilyId)->first();

        if ($family) {
            $this->familyUsers = $family->members()->get();
            $this->childrenIds = $family->members()
                ->wherePivot('role', 'child')
                ->pluck('users.id')
                ->toArray();
        } else {
            $this->familyUsers = [];
            $this->childrenIds = [];
        }

        $this->loadChores();
        $this->loadPointsAndCompletions($this->selectedFamilyId, $user->id);
        $this->loadBonusTasks();
    }

    public function updatedFilter()
    {
        $this->loadChores();
    }

    public function loadChores()
    {
        $user = Auth::user();

        $completedChoreIds = DB::table('task_user')
            ->where('user_id', $user->id)
            ->where('confirmed', true)
            ->pluck('task_id')
            ->toArray();

        if ($this->isAdult) {
            if ($this->filter === 'assigned_to_me') {
                $query = Chores::where('family_id', $this->selectedFamilyId)
                    ->whereHas('users', fn($q) => $q->where('users.id', $user->id));
            } elseif ($this->filter === 'assigned_to_children') {
                $query = Chores::where('family_id', $this->selectedFamilyId)
                    ->whereHas('users', fn($q) => $q->whereIn('users.id', $this->childrenIds))
                    ->whereDoesntHave('users', fn($q) =>
                        $q->whereIn('users.id', $this->childrenIds)
                          ->wherePivot('confirmed', true)
                    );
            } else {
                $query = Chores::where('family_id', $this->selectedFamilyId);
            }

            $this->chores = $query->whereNotIn('id', $completedChoreIds)
                ->with('users')
                ->get();
        } else {
            $this->chores = Chores::where('family_id', $this->selectedFamilyId)
                ->whereHas('users', fn($q) => $q->where('users.id', $user->id))
                ->whereNotIn('id', $completedChoreIds)
                ->with('users')
                ->get();
        }
    }

public function loadPointsAndCompletions(int $familyId, int $userId)
{
    $user = Auth::user();

    $pointsRecord = DB::table('family_user')
        ->where('family_id', $familyId)
        ->where('user_id', $userId)
        ->first(['points']);

    $this->totalPoints = $pointsRecord ? $pointsRecord->points : 0;

    $this->completedChores = DB::table('task_user')
        ->join('tasks', 'task_user.task_id', '=', 'tasks.id')
        ->where('task_user.user_id', $userId)
        ->where('task_user.confirmed', true)
        ->where('tasks.family_id', $familyId) 
        ->orderByDesc('task_user.updated_at')
        ->limit(4)
        ->get(['tasks.name', 'tasks.points']);

    if ($this->isAdult) {
        $this->pendingConfirmations = DB::table('task_user')
            ->join('tasks', 'task_user.task_id', '=', 'tasks.id')
            ->join('users', 'task_user.user_id', '=', 'users.id')
            ->whereIn('task_user.user_id', $this->childrenIds)
            ->whereNotNull('task_user.performed')
            ->where('task_user.confirmed', false)
            ->where('tasks.family_id', $familyId) 
            ->orderBy('task_user.updated_at', 'desc')
            ->get(['task_user.task_id', 'task_user.user_id', 'tasks.name as task_name', 'users.name as user_name']);
    }
}

    public function loadBonusTasks()
    {
        $user = Auth::user();

        if (!$this->selectedFamilyId) {
            $this->bonusTasks = [];
            return;
        }

        $this->bonusTasks = Chores::where('family_id', $this->selectedFamilyId)
            ->whereDoesntHave('users')
            ->get();
    }

    public function claimBonusTask($taskId)
    {
        $user = Auth::user();
        $task = Chores::find($taskId);

        if (!$task) {
            session()->flash('error', 'Task not found.');
            return;
        }

        if ($task->users()->exists()) {
            session()->flash('error', 'This task has already been claimed.');
            return;
        }

        $task->users()->attach($user->id, [
            'performed' => null,
            'confirmed' => false,
            'assigned_by' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session()->flash('message', 'Bonus task claimed!');
        $this->loadChores();
        $this->loadPointsAndCompletions($this->selectedFamilyId, $user->id);
        $this->loadBonusTasks();
    }

    public function markAsDone($choreId)
    {
        $user = Auth::user();
        
        // If adult, auto-confirm
        if ($this->isAdult) {
            $user->chores()->updateExistingPivot($choreId, [
                'performed' => now(),
                'confirmed' => true,
            ]);

            // Add points directly for adults
            $task = Chores::find($choreId);
            if ($task) {
                DB::table('family_user')
                    ->where('family_id', $this->selectedFamilyId)
                    ->where('user_id', $user->id)
                    ->increment('points', $task->points);
            }
        } else {
            // For children: needs confirmation
            $user->chores()->updateExistingPivot($choreId, [
                'performed' => now(),
                'confirmed' => false,
            ]);
        }

        $this->loadChores();
        $this->loadPointsAndCompletions($this->selectedFamilyId, $user->id);
    }

    public function confirmCompletion($taskId, $userId)
    {
        DB::table('task_user')
            ->where('task_id', $taskId)
            ->where('user_id', $userId)
            ->update([
                'confirmed' => true,
                'updated_at' => now(),
            ]);

        $user = Auth::user();
        $task = Chores::find($taskId);

        if ($user && $task) {
            DB::table('family_user')
                ->where('family_id', $this->selectedFamilyId)
                ->where('user_id', $userId)
                ->increment('points', $task->points);
        }

        $this->loadChores();
        $this->loadPointsAndCompletions($this->selectedFamilyId, $user->id);
    }

    public function deleteChore($id)
    {
        if (!$this->isAdult) {
            return;
        }

        Chores::find($id)?->delete();

        $this->loadChores();
        session()->flash('message', 'Chore deleted.');
    }

    public function render()
    {
        return view('livewire.user-chores', [
            'chores' => $this->chores,
            'completedChores' => $this->completedChores,
            'isAdult' => $this->isAdult,
            'pendingConfirmations' => $this->pendingConfirmations,
            'totalPoints' => $this->totalPoints,
            'filter' => $this->filter,
            'bonusTasks' => $this->bonusTasks,
            'userFamilies' => $this->userFamilies,
            'selectedFamilyId' => $this->selectedFamilyId,
        ]);
    }
}
