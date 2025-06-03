<div class="max-w-md mx-auto p-4 bg-white shadow rounded">
    @if (session()->has('message'))
        <div class="mb-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label class="block">Title</label>
            <input type="text" wire:model="title" class="w-full border rounded p-2">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block">Description</label>
            <input wire:model="description" class="w-full border rounded p-2 left-5"></input>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block">Assigned To</label>
            <input type="text" wire:model="assigned_to" class="w-full border rounded p-2">
            @error('assigned_to') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block">Points</label>
            <input type="number" wire:model="points" class="w-full border rounded p-2">
            @error('points') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block">frequency</label>
            <select wire:model="frequency" class="w-full border rounded p-2">
                <option value="">Select Frequency</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>
    

        <div class="mb-4">
            <label class="block">Due Date</label>
            <input type="date" wire:model="due_date" class="w-full border rounded p-2">
            @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Chore</button>
    </form>
</div>
