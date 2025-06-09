<div>
    <!-- Header with title and button -->
    <div class="flex items-center justify-between m-4">
        <h2 class="text-2xl font-semibold">Chores List</h2>
        <a href="{{ route('create-chore') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            ➕ Create New Chore
        </a>
    </div>

    <!-- List of chores -->
    <ul class="space-y-4">
        @forelse ($chores as $chore)
            <li class="m-4 bg-gray-100 rounded shadow">
                <h3 class="font-bold text-lg">{{ $chore->title }}</h3>
                <p>{{ $chore->description }}</p>
                <p class="text-sm text-gray-600">Points: {{ $chore->points }}</p>
                <p class="text-sm text-gray-600">Assigned To: {{ $chore->assigned_to }}</p>
                <p class="text-sm text-gray-600">Due: {{ \Carbon\Carbon::parse($chore->due_date)->format('M d, Y') }}</p>
                <p class="text-sm text-gray-600">Frequency: {{ $chore->frequency }}</p>
            </li>
        @empty
            <li>No chores found.</li>
        @endforelse
    </ul>
</div>