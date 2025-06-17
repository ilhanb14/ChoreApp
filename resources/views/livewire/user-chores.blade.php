<div class="min-w-sm m-6 p-6 bg-white rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-4">Your Chores</h2>

    @if (session()->has('message'))
        <div class="mb-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 text-red-600">
            {{ session('error') }}
        </div>
    @endif

    @if($isAdult)
    <div class="mb-4 flex items-center space-x-4">
        <button
            wire:click="$set('filter', 'all')"
            class="px-4 py-2 rounded shadow font-semibold transition
                {{ $filter === 'all' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Family Chores
        </button>

        <button
            wire:click="$set('filter', 'assigned_to_me')"
            class="px-4 py-2 rounded shadow font-semibold transition
                {{ $filter === 'assigned_to_me' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            My Chores
        </button>

    </div>
    @endif

    <ul class="space-y-4">
        @forelse ($chores as $chore)
            <li class="p-4 bg-gray-100 rounded-xl shadow">
                <h3 class="text-lg font-semibold">{{ $chore->name }}</h3>
                <p class="text-sm text-gray-600">Points: {{ $chore->points }}</p>
                <p class="text-sm text-gray-600">Due: {{ $chore->deadline ? \Carbon\Carbon::parse($chore->deadline)->format('M d, Y') : 'N/A' }}</p>
                <p class="text-sm text-gray-600">Frequency: {{ $chore->frequency ?? 'None' }}</p>

                {{-- Only adults can see who the chore is assigned to --}}
                @if($isAdult)
                <p class="text-sm text-gray-600">
                    Assigned to:
                    @foreach($chore->users as $user)
                        <span class="font-semibold">{{ $user->name }}</span>@if(!$loop->last), @endif
                    @endforeach
                </p>
                @endif

                @if($isAdult)
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
                @endif
            </li>
        @empty
            <li class="text-gray-600">No chores found.</li>
        @endforelse
    </ul>
</div>
