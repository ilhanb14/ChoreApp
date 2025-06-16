@include('layouts.header')
<div class="mx-4">
<livewire:tasks-calendar
    week-starts-at="1"
    before-calendar-view="vendor/livewire-calendar/navigation"
    initialYear="2025"
    initialMonth="6"
/>
</div>

@include('layouts.footer')
