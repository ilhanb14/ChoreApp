<div class= "min-h-225 bg-linear-135 from-apple-green-800 to-tangelo-900">
    <!-- Header with title and button -->
    <div class="flex items-center justify-between pt-4 pr-3 pb-2 pl-4">
        <h2 class="text-2xl font-semibold">Chores List</h2>
        <a href="{{ route('create-chore') }}"
           class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Create New Chore
        </a>
    </div>

    <!-- List of chores -->
    <ul class="space-y-4">
        @forelse ($chores as $chore)
            <li class="p-2 pl-4 pr-4 m-4 bg-gray-100 rounded-xl border-gray-200 shadow-2xl">
                <h3 class="font-bold text-lg">{{ $chore->title }}</h3>
                <p>{{ $chore->description }}</p>
                <p class="text-sm text-gray-600">Points: {{ $chore->points }}</p>
                <p class="text-sm text-gray-600">Assigned To: {{ $chore->assigned_to }}</p>
                <p class="text-sm text-gray-600">Due: {{ \Carbon\Carbon::parse($chore->due_date)->format('M d, Y') }}</p>
                <p class="text-sm text-gray-600">Frequency: {{ $chore->frequency }}</p>
                <p class="text-sm text-gray-600">Status: {{ $chore->status }}</p>
                <button wire:click="deleteChore({{ $chore->id }})"
                        wire:confirm="Are you sure you want to delete this chore?"                
                        class="float-right mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Delete
                </button>
                <button wire:click="markAsComplete({{ $chore->id }})"
                        class="mt-2  px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Mark as Complete
                </button>
                <a href="{{ route('edit-chore', ['chore' => $chore->id]) }}"
                class=" float-right mt-2 mr-2 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                Edit
                </a>

            </li>
        @empty
            <li>No chores found.</li>
        @endforelse
    </ul>
</div>