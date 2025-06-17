@include('layouts.header')
<div class="mx-4">
    <livewire:tasks-calendar
        week-starts-at="1"
        before-calendar-view="vendor/livewire-calendar/navigation"
        initialYear="{{ now()->year }}"
        initialMonth="{{ now()->month }}"
        :extras="['currentViewDate' => now()->toDateString()]"
    />
</div>
@include('layouts.footer')
