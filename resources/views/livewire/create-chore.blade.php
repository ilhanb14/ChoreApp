<div class="min-h-225 p-4 bg-linear-135 from-apple-green-800 to-tangelo-900">
    <div>
        <div class="text-center text-black mb-6">
            <h1 class="text-3xl font-bold">Create a New Chore</h1>
            <p class="text-lg">Assign chores to your family members and earn points!</p>
        </div>

        <div class="max-w-md mx-auto p-4 bg-white shadow rounded-xl ">


            @if (session()->has('message'))
                <div class="mb-4 text-green-600">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="save" class="w-100">
                <h2 class="text-xl font-bold mb-4">Create a New Chore</h2>

                {{-- Title --}}
                <div class="mb-4">
                    <label class="block">Title</label>
                    <input 
                        type="text"
                        wire:model="title"
                        class="w-full border-2 border-gray-200 rounded-xl p-2"
                        placeholder="Enter chore title"
                    >
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                            {{-- Family Switcher --}}
            @if(count($userFamilies) > 0)
                <div class="mb-6">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Select Family</label>
                    <select 
                        wire:change="changeFamily($event.target.value)"
                        class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-tangelo focus:border-tangelo transition-all duration-300"
                    >
                        @foreach($userFamilies as $family)
                            <option value="{{ $family->id }}" {{ $selectedFamilyId == $family->id ? 'selected' : '' }}>
                                {{ $family->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif


                {{-- Assigned To --}}
                <div class="mb-4">
                    <label class="block">Assigned To</label>
                    <select wire:model="assigned_to" class="w-full border-2 border-gray-200 rounded-xl p-2">
                        <option value="">Bonus task</option>
                        @foreach ($familyUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Points --}}
                <div class="mb-4">
                    <label class="block">Points</label>
                    <input 
                        type="number"
                        wire:model="points"
                        class="w-full border-2 border-gray-200 rounded-xl p-2"
                    >
                    @error('points') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Recurring --}}
                <div x-data="{ showFrequency: @entangle('isRecurring') }">
                    <label class="flex items-center mb-2">
                        <input type="checkbox" x-model="showFrequency" wire:model="isRecurring" class="mr-2">
                        Is Recurring?
                    </label>

                    <div class="mb-4" x-show="showFrequency" x-transition>
                        <label class="block">Frequency</label>
                        <select wire:model="frequency" class="w-full border-2 border-gray-200 rounded-xl p-2">
                            <option value="">Select Frequency</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                        @error('frequency') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Due Date --}}
                <div class="mb-4">
                    <label class="block">Due Date</label>
                    <input 
                        type="date"
                        wire:model="due_date"
                        class="w-full border-2 border-gray-200 rounded-xl p-2"
                    >
                    @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl">
                    Save Chore
                </button>
            </form>
        </div>
    </div>
</div>
