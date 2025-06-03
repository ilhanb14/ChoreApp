<div class="min-h-220 flex items-center justify-center p-4 bg-linear-135 from-apple-green-800 to-tangelo-900">
    <div class="max-w-md mx-auto p-4 bg-white shadow rounded-xl ">
    @if (session()->has('message'))
        <div class="mb-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="w-100">
        <h2 class="text-xl font-bold mb-4">Create a New Chore</h2>
        <div class="mb-4">
            <label class="block">Title</label>
            <input 
            type="text-sm" 
            wire:model="title" 
            class="w-full border-2 border-gray-200 rounded-xl p-2" 
            placeholder="Enter chore title">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block">Description</label>
            <textarea 
            wire:model="description" 
            class="w-full border-2 border-gray-200 rounded-xl p-2 left-5"
            placeholder="Enter chore description"
            rows="4"
            ></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block">Assigned To</label>
            <input 
            type="text"
            wire:model="assigned_to" 
            class="w-full border-2 border-gray-200 rounded-xl p-2"
            placeholder="select a user to assign the chore to">
            @error('assigned_to') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block">Points</label>
            <input 
            type="number" 
            wire:model="points" 
            class="w-full border-2 border-gray-200 rounded-xl p-2">
            @error('points') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block">frequency</label>
            <select wire:model="frequency" class="w-full border-2 border-gray-200 rounded-xl p-2">
                <option value="">Select Frequency</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>
    

        <div class="mb-4">
            <label class="block">Due Date</label>
            <input 
            type="date" 
            wire:model="due_date" 
            class="w-full border-2 border-gray-200 rounded-xl p-2">
            @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl">Save Chore</button>
    </form>
</div>
