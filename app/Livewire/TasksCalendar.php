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
    public $currentViewDate;

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
        $this->currentViewDate = Carbon::now();
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
                $date = $task->deadline ?? $task->start_date;
                
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
        if ($task->deadline) {
            $originalTime = Carbon::parse($task->deadline);
            $newDate->setTime($originalTime->hour, $originalTime->minute);
            $task->deadline = $newDate;
        } else {
            $originalTime = Carbon::parse($task->start_date);
            $newDate->setTime($originalTime->hour, $originalTime->minute);
            $task->start_date = $newDate;
        }
        
        $task->save();
    }

    // View Mode Switching
    public function switchToMonthView()
    {
        $this->viewMode = 'month';
        // Update the month view to show the current view date's month
        $this->startsAt = $this->currentViewDate->copy()->startOfMonth();
    }

    public function switchToWeekView()
    {
        $this->viewMode = 'week';
    }

    public function switchToDayView()
    {
        $this->viewMode = 'day';
    }

    // Custom render method to handle different views
    public function render()
    {
        if ($this->viewMode === 'week') {
            return view('livewire.tasks-calendar-week', [
                'events' => $this->events(),
                'weekDays' => $this->getWeekDays(),
                'currentDate' => $this->currentViewDate,
                'userFamilies' => $this->userFamilies,
                'selectedFamilyId' => $this->selectedFamilyId,
                'viewMode' => $this->viewMode,
            ]);
        } elseif ($this->viewMode === 'day') {
            return view('livewire.tasks-calendar-day', [
                'events' => $this->events(),
                'currentDate' => $this->currentViewDate,
                'userFamilies' => $this->userFamilies,
                'selectedFamilyId' => $this->selectedFamilyId,
                'viewMode' => $this->viewMode,
            ]);
        }
        
        // Default to parent month view
        return parent::render();
    }

    // Get days for week view
    public function getWeekDays()
    {
        $startOfWeek = $this->currentViewDate->copy()->startOfWeek();
        $days = collect();

        for ($i = 0; $i < 7; $i++) {
            $days->push($startOfWeek->copy()->addDays($i));
        }

        return $days;
    }

    // Week Navigation
    public function goToPreviousWeek()
    {
        $this->currentViewDate = $this->currentViewDate->copy()->subWeek();
    }

    public function goToNextWeek()
    {
        $this->currentViewDate = $this->currentViewDate->copy()->addWeek();
    }

    public function goToCurrentWeek()
    {
        $this->currentViewDate = Carbon::today();
    }

    // Day Navigation
    public function goToPreviousDay()
    {
        $this->currentViewDate = $this->currentViewDate->copy()->subDay();
    }

    public function goToNextDay()
    {
        $this->currentViewDate = $this->currentViewDate->copy()->addDay();
    }

    public function goToCurrentDay()
    {
        $this->currentViewDate = Carbon::today();
    }

    // Get current date for display
    public function getCurrentDisplayDate()
    {
        if ($this->viewMode === 'month') {
            return $this->startsAt;
        }
        return $this->currentViewDate;
    }

    public function onDayClick($year, $month, $day)
    {
        $this->currentViewDate = Carbon::create($year, $month, $day);
        $this->viewMode = 'day';
    }
}