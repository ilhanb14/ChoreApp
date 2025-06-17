{{-- Custom Week View: resources/views/livewire/tasks-calendar-week.blade.php --}}
<div>
    {{-- Navigation --}}
    @include('vendor.livewire-calendar.navigation')

    {{-- Week Days Header --}}
    <div class="grid grid-cols-7 gap-0 mb-4">
        @foreach($weekDays as $day)
            <div class="bg-indigo-100 border border-gray-200 p-4 text-center">
                <div class="font-semibold text-gray-900">{{ $day->format('l') }}</div>
                <div class="text-sm text-gray-600">{{ $day->format('M j') }}</div>
            </div>
        @endforeach
    </div>

    {{-- Week Days Content --}}
    <div class="grid grid-cols-7 gap-0" style="min-height: 500px;">
        @foreach($weekDays as $day)
            @php
                $dayEvents = $events->filter(function($event) use ($day) {
                    return $event['date']->format('Y-m-d') === $day->format('Y-m-d');
                });
                $isToday = $day->isToday();
            @endphp
            
            <div
                ondragenter="onLivewireCalendarEventDragEnter(event, '{{ $this->getId() }}', '{{ $day->format('Y-m-d') }}', '{{ $dragAndDropClasses }}');"
                ondragleave="onLivewireCalendarEventDragLeave(event, '{{ $this->getId() }}', '{{ $day->format('Y-m-d') }}', '{{ $dragAndDropClasses }}');"
                ondragover="onLivewireCalendarEventDragOver(event);"
                ondrop="onLivewireCalendarEventDrop(event, '{{ $this->getId() }}', '{{ $day->format('Y-m-d') }}', {{ $day->year }}, {{ $day->month }}, {{ $day->day }}, '{{ $dragAndDropClasses }}');"
                class="border border-gray-200 bg-white flex flex-col"
                id="{{ $this->getId() }}-{{ $day->format('Y-m-d') }}"
                style="min-height: 500px;">
                
                <div
                    wire:click="onDayClick({{ $day->year }}, {{ $day->month }}, {{ $day->day }})"
                    class="p-3 h-full {{ $isToday ? 'bg-yellow-50' : 'bg-white' }} flex flex-col cursor-pointer hover:bg-gray-50 transition-colors">
                    
                    {{-- Day Number --}}
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-lg font-semibold {{ $isToday ? 'text-tangelo' : 'text-gray-900' }}">
                            {{ $day->format('j') }}
                        </span>
                        @if($dayEvents->isNotEmpty())
                            <span class="text-xs text-gray-500">
                                {{ $dayEvents->count() }} {{ Str::plural('chore', $dayEvents->count()) }}
                            </span>
                        @endif
                    </div>

                    {{-- Events --}}
                    <div class="flex-1 overflow-y-auto space-y-1">
                        @foreach($dayEvents as $event)
                            <div
                                draggable="true"
                                ondragstart="onLivewireCalendarEventDragStart(event, '{{ $event['id'] }}')">
                                
                                <div
                                    wire:click.stop="onEventClick('{{ $event['id'] }}')"
                                    class="bg-white rounded-lg border py-2 px-3 shadow-md cursor-pointer hover:bg-picton-blue-900 hover:shadow-lg transition-shadow">
                                    <p class="text-sm font-medium">
                                        {{ $event['title'] }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-600">
                                        {{ $event['description'] ?? 'No description' }}
                                    </p>
                                    @if($event['points'])
                                        <p class="mt-1 text-xs text-tangelo font-medium">
                                            {{ $event['points'] }} points
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>