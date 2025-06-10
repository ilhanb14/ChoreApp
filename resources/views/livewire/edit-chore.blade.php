<div class="min-h-225 bg-linear-135 from-apple-green-800 to-tangelo-900 ">
    <div class="flex justify-end">

    </div>
<div class="max-w-xl mx-auto p-4 rounded">
    <h2 class="text-2xl font-bold mb-4">Edit Chore</h2>

    @if (session()->has('message'))
        <div class="mb-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif
    <div class="mb-4 bg-white p-4 rounded-xl shadow">
        
    
    <form wire:submit.prevent="updateChore" class="space-y-4">
        <div>
            <label class="block pb-2 text-sm font-semibold">Title</label>
            <input type="text" wire:model="title" class="w-full border-2 border-gray-200 rounded-xl p-2" required>
        </div>

        <div>
            <label class="block pb-2 text-sm font-semibold">Description</label>
            <textarea wire:model="description" class="w-full border-2 border-gray-200 rounded-xl p-2"></textarea>
        </div>

        <div>
            <label class="block pb-2 text-sm font-semibold">Points</label>
            <input type="number" wire:model="points" class="w-full border-2 border-gray-200 rounded-xl p-2" required>
        </div>

        <div>
            <label class="block pb-2 text-sm font-semibold">Assigned To</label>
            <input type="text" wire:model="assigned_to" class="w-full border-2 border-gray-200 rounded-xl p-2">
        </div>

        <div>
            <label class="block pb-2 text-sm font-semibold">Due Date</label>
            <input type="date" wire:model="due_date" class="w-full border-2 border-gray-200 rounded-xl p-2" required>
        </div>

        <div>
            <label class="block  text-sm font-semibold">Frequency</label>
            <select type="text" wire:model="frequency" class="w-full border-2 border-gray-200 rounded-xl p-2">
                <option value="">Select Frequency</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
        </div>
            </select>

        <div class="flex justify-between mt-4">
            <a href="{{ route('chores') }}" class="px-5 py-3 bg-gray-500 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl">
                Back
            </a>
            <button type="submit" class="px-4 py-2w-full py-3 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl">Save Changes</button>
            
        </div>
</div>
    </form>
</div>
