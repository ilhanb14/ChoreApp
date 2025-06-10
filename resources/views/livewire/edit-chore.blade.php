<div class="min-h-225 bg-linear-135 from-apple-green-800 to-tangelo-900 ">
    <div class="flex justify-end">

    </div>
<div class="max-w-xl mx-auto p-4 shadow rounded">
    <h2 class="text-2xl font-bold mb-4">Edit Chore</h2>

    @if (session()->has('message'))
        <div class="mb-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif
    <div class="mb-4 bg-white p-4 rounded-xl shadow">
        
    
    <form wire:submit.prevent="updateChore" class="space-y-4">
        <div>
            <label class="block text-sm font-semibold">Title</label>
            <input type="text" wire:model="title" class="w-full  border rounded p-2" required>
        </div>

        <div>
            <label class="block text-sm font-semibold">Description</label>
            <textarea wire:model="description" class="w-full border rounded p-2"></textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold">Points</label>
            <input type="number" wire:model="points" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-sm font-semibold">Assigned To</label>
            <input type="text" wire:model="assigned_to" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-sm font-semibold">Due Date</label>
            <input type="date" wire:model="due_date" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-sm font-semibold">Frequency</label>
            <select type="text" wire:model="frequency" class="w-full border rounded p-2">
                <option value="">Select Frequency</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
        </div>
            </select>

        <div class="flex justify-between mt-4">
            <a href="{{ route('chores') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Back
            </a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            Save Changes
            </button>
        </div>
</div>
    </form>
</div>
