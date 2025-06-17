<div class="min-w-sm m-6 p-4 bg-white rounded-xl shadow>
    <h2 class="text-2xl font-bold mb-4">Your Chores</h2>

    @if (session()->has('message'))
        <div class="mb-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif

    <ul class="space-y-4">
        @forelse ($chores as $chore)
            <li class="p-4 bg-gray-100 rounded-xl shadow">
                <h3 class="text-lg font-semibold">{{ $chore->title }}</h3>
                <p>{{ $chore->description }}</p>
                <p class="text-sm text-gray-600">Points: {{ $chore->points }}</p>
                <p class="text-sm text-gray-600">Assigned To: {{ $chore->assigned_to }}</p>
                <p class="text-sm text-gray-600">Due: {{ \Carbon\Carbon::parse($chore->due_date)->format('M d, Y') }}</p>
                <p class="text-sm text-gray-600">Frequency: {{ $chore->frequency }}</p>
                <p class="text-sm text-gray-600">Status: {{ $chore->status }}</p>
                <div class="flex justify-between mt-4">
                    <a href="{{ route('edit-chore', $chore->id) }}"
                       class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                        Edit
                    </a>
                    <button wire:click="deleteChore({{ $chore->id }})"
                            wire:confirm="Are you sure you want to delete this chore?"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </li>
        @empty
            <li class="text-gray-600">No chores found.</li>
        @endforelse
    </ul>

</div>