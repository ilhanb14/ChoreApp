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
    
    <!-- Month/Year Display -->
    <div class="text-2xl font-bold text-tangelo">
        {{ $startsAt->format('F Y') }}
    </div>
    
    <!-- Navigation Buttons -->
    <div class="flex items-center gap-3">
        <button 
            wire:click="goToPreviousMonth"
            class="px-4 py-2 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-medium rounded-xl shadow transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg"
            aria-label="Previous month"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        
        <button 
            wire:click="goToCurrentMonth"
            class="px-4 py-2 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-medium rounded-xl shadow transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg"
        >
            Today
        </button>
        
        <button 
            wire:click="goToNextMonth"
            class="px-4 py-2 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-medium rounded-xl shadow transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg"
            aria-label="Next month"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>

@script
<script>
    Livewire.on('refreshCalendar', () => {
        // This will force the calendar to refresh
        Livewire.dispatch('refreshCalendar');
    });
</script>
@endscript