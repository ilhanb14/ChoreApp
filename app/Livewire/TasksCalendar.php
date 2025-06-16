<?php

namespace App\Livewire;

use Omnia\LivewireCalendar\LivewireCalendar;
use Illuminate\Support\Collection;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TasksCalendar extends LivewireCalendar
{
    public $selectedFamilyId;
    public $userFamilies = [];
    public $viewMode = 'month';

    // Enable drag and drop
    public $dragAndDropEnabled = true;
    public $dragAndDropClasses = 'cursor-move bg-white shadow rounded p-2';

    public function mount(
        $initialYear = null, 
        $initialMonth = null, 
        $weekStartsAt = null,
        $calendarView = null,
        $dayView = null,
        $eventView = null,
        $dayOfWeekView = null,
        $dragAndDropClasses = null,
        $beforeCalendarView = null,
        $afterCalendarView = null,
        $pollMillis = null,
        $pollAction = null,
        $dragAndDropEnabled = true,
        $dayClickEnabled = true,
        $eventClickEnabled = true,
        $extras = []
    ) {
        parent::mount(
            $initialYear,
            $initialMonth,
            $weekStartsAt,
            $calendarView,
            $dayView,
            $eventView,
            $dayOfWeekView,
            $dragAndDropClasses,
            $beforeCalendarView,
            $afterCalendarView,
            $pollMillis,
            $pollAction,
            $dragAndDropEnabled,
            $dayClickEnabled,
            $eventClickEnabled,
            $extras
        );

        $this->loadUserFamilies();
    }

    protected function loadUserFamilies()
    {
        if (Auth::check()) {
            $this->userFamilies = Auth::user()->families()->get();
            $this->selectedFamilyId = session('current_family_id') ?? ($this->userFamilies->first() ? $this->userFamilies->first()->id : null);
        }
    }

    public function events(): Collection
    {
        if (!$this->selectedFamilyId) {
            return collect([]);
        }

        return Task::query()
            ->where('family_id', $this->selectedFamilyId)
            ->where(function($query) {
                $query->whereNotNull('start_date')
                      ->orWhereNotNull('deadline');
            })
            ->get()
            ->map(function (Task $task) {
                $date = $task->start_date ?? $task->deadline;
                
                return [
                    'id' => $task->id,
                    'title' => $task->name,
                    'description' => $task->description,
                    'date' => Carbon::parse($date),
                    'points' => $task->points,
                    'recurring' => $task->recurring,
                ];
            });
    }

    public function changeFamily($familyId)
    {
        $this->selectedFamilyId = $familyId;
        session(['current_family_id' => $familyId]);
    }

    public function onEventDropped($eventId, $year, $month, $day)
    {
        $task = Task::find($eventId);
        
        if (!$task) {
            return;
        }
        
        $newDate = Carbon::create($year, $month, $day);
        
        // Update start_date or deadline while preserving time
        if ($task->start_date) {
            $originalTime = Carbon::parse($task->start_date);
            $newDate->setTime($originalTime->hour, $originalTime->minute);
            $task->start_date = $newDate;
        } else {
            $originalTime = Carbon::parse($task->deadline);
            $newDate->setTime($originalTime->hour, $originalTime->minute);
            $task->deadline = $newDate;
        }
        
        $task->save();
    }

    public function switchToMonthView()
    {
        $this->viewMode = 'month';
    }

    public function switchToWeekView()
    {
        $this->viewMode = 'week';
    }

    public function switchToDayView()
    {
        $this->viewMode = 'day';
    }

        // Override the default calendar view
    public function calendarView()
    {
        if ($this->viewMode === 'week') {
            return 'vendor.livewire-calendar.week';
        } elseif ($this->viewMode === 'day') {
            return 'vendor.livewire-calendar.day-single';
        }
        return parent::calendarView();
    }

    // Get days for week view
    public function getWeekDaysProperty()
    {
        $startOfWeek = $this->startsAt->copy()->startOfWeek();
        $days = collect();

        for ($i = 0; $i < 7; $i++) {
            $days->push($startOfWeek->copy()->addDays($i));
        }

        return $days;
    }
}