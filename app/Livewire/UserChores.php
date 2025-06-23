<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Chores;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function mount()
    {
        $user = Auth::user();
        $family = $user->families()->first();

        if (!$family) {
            $this->chores = collect();
            $this->completedChores = collect();
            $this->bonusTasks = collect();
            return;
        }

        $this->isAdult = $user->families()
            ->where('family_id', $family->id)
            ->wherePivot('role', 'adult')
            ->exists();

        $this->familyUsers = $family->members()->get();

        $this->childrenIds = $family->members()
            ->wherePivot('role', 'child')
            ->pluck('users.id')
            ->toArray();

        $this->loadChores();
        $this->loadPointsAndCompletions($family->id, $user->id);
        $this->loadBonusTasks($family->id);
    }

    public function updatedFilter()
    {
        $this->loadChores();
    }

    public function loadChores()
    {
        $user = Auth::user();
        $family = $user->families()->first();

        $completedChoreIds = DB::table('task_user')
            ->where('user_id', $user->id)
            ->where('confirmed', true)
            ->pluck('task_id')
            ->toArray();

        if ($this->isAdult) {
            if ($this->filter === 'assigned_to_me') {
                $query = Chores::where('family_id', $family->id)
                    ->whereHas('users', fn($q) => $q->where('users.id', $user->id));
            } elseif ($this->filter === 'assigned_to_children') {
                $query = Chores::where('family_id', $family->id)
                    ->whereHas('users', fn($q) => $q->whereIn('users.id', $this->childrenIds))
                    ->whereDoesntHave('users', fn($q) =>
                        $q->whereIn('users.id', $this->childrenIds)
                          ->wherePivot('confirmed', true)
                    );
            } else {
                $query = Chores::where('family_id', $family->id);
            }

            $this->chores = $query->whereNotIn('id', $completedChoreIds)
                ->with('users')
                ->get();
        } else {
            $this->chores = $user->chores()
                ->whereNotIn('tasks.id', $completedChoreIds)
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

        if (!$this->isAdult) {
            $this->completedChores = DB::table('task_user')
                ->join('tasks', 'task_user.task_id', '=', 'tasks.id')
                ->where('task_user.user_id', $user->id)
                ->where('task_user.confirmed', true)
                ->orderByDesc('task_user.updated_at')
                ->limit(4)
                ->get(['tasks.name', 'tasks.points']);
        } else {
            $this->pendingConfirmations = DB::table('task_user')
                ->join('tasks', 'task_user.task_id', '=', 'tasks.id')
                ->join('users', 'task_user.user_id', '=', 'users.id')
                ->whereIn('task_user.user_id', $this->childrenIds)
                ->whereNotNull('task_user.performed')
                ->where('task_user.confirmed', false)
                ->orderBy('task_user.updated_at', 'desc')
                ->get(['task_user.task_id', 'task_user.user_id', 'tasks.name as task_name', 'users.name as user_name']);
        }
    }

    public function loadBonusTasks()
{
    $user = Auth::user();
    $family = $user->families()->first();

    if (!$family) {
        $this->bonusTasks = [];
        return;
    }

    // Fetch tasks in the family that have no users assigned yet
    $this->bonusTasks = Chores::where('family_id', $family->id)
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

    // Don't allow claiming if already claimed
    if ($task->users()->exists()) {
        session()->flash('error', 'This task has already been claimed.');
        return;
    }

    // Assign the current user
        $task->users()->attach($user->id, [
            'performed' => null,
            'confirmed' => false,
            'assigned_by' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
    ]);

        session()->flash('message', 'Bonus task claimed!');
        $this->loadChores();
        $this->loadPointsAndCompletions($user->families()->first()->id, $user->id);
        $this->loadBonusTasks();
    }

    public function markAsDone($choreId)
    {
        $user = Auth::user();
        $user->chores()->updateExistingPivot($choreId, [
            'performed' => now(),
            'confirmed' => false,
        ]);

        $this->loadChores();
        $this->loadPointsAndCompletions($user->families()->first()->id, $user->id);
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
            $family = $user->families()->first();

            if ($family) {
                DB::table('family_user')
                    ->where('family_id', $family->id)
                    ->where('user_id', $userId)
                    ->increment('points', $task->points);
            }
        }


        $this->loadChores();
        $this->loadPointsAndCompletions($user->families()->first()->id, $user->id);
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
        ]);
    }
}