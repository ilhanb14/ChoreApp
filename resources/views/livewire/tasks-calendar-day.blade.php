{{-- Custom Day View: resources/views/livewire/tasks-calendar-day.blade.php --}}
<div>
    {{-- Navigation --}}
    @include('vendor.livewire-calendar.navigation')

    <div class="max-w-4xl mx-auto">
        {{-- Day Header --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    {{ $currentDate->format('l') }}
                </h2>
                <p class="text-xl text-gray-600">
                    {{ $currentDate->format('F j, Y') }}
                </p>
            </div>
        </div>

        {{-- Day Content --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @php
                $dayEvents = $events->filter(function($event) {
                    return $event['date']->format('Y-m-d') === $this->currentViewDate->format('Y-m-d');
                });
                $isToday = $currentDate->isToday();
            @endphp
            
            <div
                ondragenter="onLivewireCalendarEventDragEnter(event, '{{ $this->getId() }}', '{{ $currentDate->format('Y-m-d') }}', '{{ $dragAndDropClasses }}');"
                ondragleave="onLivewireCalendarEventDragLeave(event, '{{ $this->getId() }}', '{{ $currentDate->format('Y-m-d') }}', '{{ $dragAndDropClasses }}');"
                ondragover="onLivewireCalendarEventDragOver(event);"
                ondrop="onLivewireCalendarEventDrop(event, '{{ $this->getId() }}', '{{ $currentDate->format('Y-m-d') }}', {{ $currentDate->year }}, {{ $currentDate->month }}, {{ $currentDate->day }}, '{{ $dragAndDropClasses }}');"
                class="min-h-96"
                id="{{ $this->getId() }}-{{ $currentDate->format('Y-m-d') }}">
                
                <div
                    wire:click="onDayClick({{ $currentDate->year }}, {{ $currentDate->month }}, {{ $currentDate->day }})"
                    class="p-6 {{ $isToday ? 'bg-yellow-50/50' : 'bg-white' }} min-h-96">
                    
                    {{-- Events Header --}}
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            @if($dayEvents->isNotEmpty())
                                Chores for today ({{ $dayEvents->count() }})
                            @else
                                No chores scheduled
                            @endif
                        </h3>
                        
                        @if($isToday)
                            <span class="px-3 py-1 bg-tangelo text-white text-sm font-medium rounded-full">
                                Today
                            </span>
                        @endif
                    </div>

                    {{-- Events List --}}
                    @if($dayEvents->isNotEmpty())
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($dayEvents as $event)
                                <div
                                    draggable="true"
                                    ondragstart="onLivewireCalendarEventDragStart(event, '{{ $event['id'] }}')">
                                    
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200 cursor-move">
                                        <div
                                            wire:click.stop="onEventClick('{{ $event['id'] }}')"
                                            class="cursor-pointer">
                                            <p class="text-sm font-medium">
                                                {{ $event['title'] }}
                                            </p>
                                            <p class="mt-2 text-xs text-gray-600">
                                                {{ $event['description'] ?? 'No description' }}
                                            </p>
                                        </div>
                                        
                                        {{-- Additional task details for day view --}}
                                        @if($event['points'] || $event['date'])
                                            <div class="mt-2 flex items-center justify-between">
                                                <span class="text-xs text-gray-500">
                                                    {{ $event['date']->format('g:i A') }}
                                                </span>
                                                @if($event['points'])
                                                    <span class="px-2 py-1 bg-tangelo/10 text-tangelo text-xs font-medium rounded">
                                                        {{ $event['points'] }} pts
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($event['recurring'])
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Recurring
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No chores scheduled</h3>
                            <p class="mt-1 text-sm text-gray-500">This day is free of scheduled chores.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>