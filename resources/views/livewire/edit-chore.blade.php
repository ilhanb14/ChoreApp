<div class="min-h-225 p-4 bg-linear-135 from-apple-green-800 to-tangelo-900">
    <div>
        <div class="text-center text-black mb-6">
            <h1 class="text-3xl font-bold">Edit Chore</h1>
            <p class="text-lg">Update your family's tasks and responsibilities</p>
        </div>

        <div class="max-w-md mx-auto p-4 bg-white shadow rounded-xl">
            @if (session()->has('message'))
                <div class="mb-4 text-green-600 font-semibold">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="updateChore" class="w-100">
                <h2 class="text-xl font-bold mb-4">Edit Chore</h2>

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

                {{-- Description --}}
                <div class="mb-4">
                    <label class="block">Description</label>
                    <textarea 
                        wire:model="description"
                        class="w-full border-2 border-gray-200 rounded-xl p-2"
                        placeholder="Enter chore description"
                        rows="4"
                    ></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Assigned To --}}
                <div class="mb-4">
                    <label class="block">Assigned To</label>
                        <select wire:model="assigned_to" class="w-full border-2 border-gray-200 rounded-xl p-2">
                            <option value="">Select a family member</option>
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

                <div class="flex justify-between gap-2">
                    <a href="{{ route('chores') }}" class="w-full py-3 px-6 bg-gray-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all text-center">
                        Cancel
                    </a>
                    <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
