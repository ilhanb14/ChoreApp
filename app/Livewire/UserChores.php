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
    public $filter = 'assigned_to_me'; // only 'assigned_to_me' & 'assigned_to_children'
    public $familyUsers = [];
    public $childrenIds = [];
    public $totalPoints = 0;
    public $pendingConfirmations = [];

    public function mount()
    {
        $user = Auth::user();
        $family = $user->families()->first();

        if (!$family) {
            $this->chores = collect();
            $this->completedChores = collect();
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
        $this->loadPointsAndCompletions();
    }

    public function updatedFilter()
    {
        $this->loadChores();
    }

   public function loadChores()
{
    $user = Auth::user();
    $family = $user->families()->first();

    // IDs of chores confirmed as completed by this user
    $completedChoreIds = DB::table('task_user')
        ->where('user_id', $user->id)
        ->where('confirmed', true)
        ->pluck('task_id')
        ->toArray();

    if ($this->isAdult) {
        if ($this->filter === 'assigned_to_me') {
            $query = Chores::where('family_id', $family->id)
                ->whereHas('users', fn($q) => $q->where('users.id', $user->id));
            $query->whereNotIn('id', $completedChoreIds);
        } elseif ($this->filter === 'assigned_to_children') {
            $completedByChildrenIds = DB::table('task_user')
                ->whereIn('user_id', $this->childrenIds)
                ->where('confirmed', true)
                ->pluck('task_id')
                ->toArray();

            $query = Chores::where('family_id', $family->id)
                ->whereHas('users', fn($q) => $q->whereIn('users.id', $this->childrenIds))
                ->whereNotIn('id', $completedByChildrenIds);
        } else {
            $query = Chores::where('family_id', $family->id);
        }

        $this->chores = $query->with('users')->get();
    } else {
        // Children see chores assigned to them NOT confirmed completed
        $this->chores = $user->chores()
            ->whereNotIn('tasks.id', $completedChoreIds)
            ->with('users')
            ->get();
    }
}
    public function loadPointsAndCompletions()
    {
        $user = Auth::user();

        $this->totalPoints = DB::table('task_user')
            ->join('tasks', 'task_user.task_id', '=', 'tasks.id')
            ->where('task_user.user_id', $user->id)
            ->where('task_user.confirmed', true)
            ->sum('tasks.points');

        if (!$this->isAdult) {
            $this->completedChores = DB::table('task_user')
                ->join('tasks', 'task_user.task_id', '=', 'tasks.id')
                ->where('task_user.user_id', $user->id)
                ->where('task_user.confirmed', true)
                ->orderByDesc('task_user.updated_at')
                ->limit(6)
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

    public function markAsDone($choreId)
    {
        $user = Auth::user();
        $user->chores()->updateExistingPivot($choreId, [
            'performed' => now(),
            'confirmed' => false,
        ]);

        $this->loadChores();
        $this->loadPointsAndCompletions();
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



        $this->loadChores();
        $this->loadPointsAndCompletions();
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
        ]);
    }
}
