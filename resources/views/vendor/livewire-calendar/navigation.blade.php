<div class="flex items-center justify-between my-6 p-4 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
    <!-- Family Selector Dropdown -->
    @if(count($userFamilies) > 0)
        <div class="w-64">
            <select 
                wire:model="selectedFamilyId"
                wire:change="changeFamily($event.target.value)"
                class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-tangelo focus:border-tangelo transition-all duration-300"
            >
                @foreach($userFamilies as $family)
                    <option value="{{ $family->id }}">{{ $family->name }}</option>
                @endforeach
            </select>
        </div>
    @else
        <div class="text-sm text-gray-500">
            No families available
        </div>
    @endif
    
    <!-- View Mode Selector -->
    <div class="flex bg-gray-100 rounded-xl p-1">
        <button 
            wire:click="switchToMonthView"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $viewMode === 'month' ? 'bg-white text-tangelo shadow-sm' : 'text-gray-600 hover:text-gray-800' }}"
        >
            Month
        </button>
        <button 
            wire:click="switchToWeekView"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $viewMode === 'week' ? 'bg-white text-tangelo shadow-sm' : 'text-gray-600 hover:text-gray-800' }}"
        >
            Week
        </button>
        <button 
            wire:click="switchToDayView"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $viewMode === 'day' ? 'bg-white text-tangelo shadow-sm' : 'text-gray-600 hover:text-gray-800' }}"
        >
            Day
        </button>
    </div>
    
    <!-- Month/Year Display -->
    <div class="text-2xl font-bold text-tangelo">
        @if($viewMode === 'month')
            {{ $startsAt->format('F Y') }}
        @elseif($viewMode === 'week')
            Week of {{ $currentViewDate->startOfWeek()->format('M j') }}
        @else
            {{ $currentViewDate->format('F j, Y') }}
        @endif
    </div>
    
    <!-- Navigation Buttons -->
    <div class="flex items-center gap-3">
        <button 
            @if($viewMode === 'month')
                wire:click="goToPreviousMonth"
            @elseif($viewMode === 'week')
                wire:click="goToPreviousWeek"
            @else
                wire:click="goToPreviousDay"
            @endif
            class="px-4 py-2 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-medium rounded-xl shadow transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg"
            aria-label="Previous {{ $viewMode }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        
        <button 
            @if($viewMode === 'month')
                wire:click="goToCurrentMonth"
            @elseif($viewMode === 'week')
                wire:click="goToCurrentWeek"
            @else
                wire:click="goToCurrentDay"
            @endif
            class="px-4 py-2 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-medium rounded-xl shadow transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg"
        >
            Today
        </button>
        
        <button 
            @if($viewMode === 'month')
                wire:click="goToNextMonth"
            @elseif($viewMode === 'week')
                wire:click="goToNextWeek"
            @else
                wire:click="goToNextDay"
            @endif
            class="px-4 py-2 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-medium rounded-xl shadow transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg"
            aria-label="Next {{ $viewMode }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>