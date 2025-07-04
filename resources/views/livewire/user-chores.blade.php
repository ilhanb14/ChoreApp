<div class="flex flex-nowrap gap-6 p-6 overflow-x-auto">

  <!-- Your Chores Box -->
  <div class="flex-grow basis-[60%] min-w-[400px] bg-white rounded-xl shadow p-6">
  @if(count($userFamilies) > 1)
    <div class="w-60">
        <select 
            wire:change="changeFamily($event.target.value)"
            class="w-full px-4 py-2 mb-4  bg-white border border-gray-300 rounded ">
            @foreach($userFamilies as $family)
                <option value="{{ $family->id }}" {{ $family->id == $selectedFamilyId ? 'selected' : '' }}>
                {{ $family->name }}
              </option>
            @endforeach
          </select>
      </div>
  @endif
    <h2 class="text-2xl font-bold mb-4">Your Chores</h2>

    @if (session()->has('message'))
      <div class="mb-4 text-green-600">{{ session('message') }}</div>
    @endif
    @if (session()->has('error'))
      <div class="mb-4 text-red-600">{{ session('error') }}</div>
    @endif

    @if($isAdult)
      <div class="mb-4 flex items-center space-x-4">
        <button wire:click="$set('filter', 'assigned_to_me')" class="px-4 py-2 rounded shadow font-semibold transition {{ $filter === 'assigned_to_me' ? 'bg-apple-green text-white' : 'bg-gray-200 hover:bg-apple-green-800 text-gray-700' }}">Assigned to Me</button>
        <button wire:click="$set('filter', 'assigned_to_children')" class="px-4 py-2 rounded shadow font-semibold transition {{ $filter === 'assigned_to_children' ? 'bg-apple-green text-white' : 'bg-gray-200 hover:bg-apple-green-800 text-gray-700' }}">Assigned to Children</button>
      </div>
    @endif

    <ul class="space-y-4">
      @forelse ($chores as $chore)
        <li class="p-4 bg-gray-100 rounded-xl shadow flex flex-col">
          <div>
            <h3 class="text-lg font-semibold">{{ $chore->name }}</h3>
            <p class="text-sm text-gray-600">Points: {{ $chore->points }}</p>
            <p class="text-sm text-gray-600">Due: {{ $chore->deadline ? \Carbon\Carbon::parse($chore->deadline)->format('M d, Y') : 'N/A' }}</p>
            <p class="text-sm text-gray-600">Frequency: {{ $chore->frequency ?? 'None' }}</p>

            @if($isAdult)
              <p class="text-sm text-gray-600">
                Assigned to:
                @foreach($chore->users as $user)
                  <span class="font-semibold">{{ $user->name }}</span>@if(!$loop->last), @endif
                @endforeach
              </p>
            @endif
          </div>

          @php
            $pivot = $chore->users->firstWhere('id', auth()->id())?->pivot;
          @endphp

          @if($isAdult)
            <div class="flex justify-between mt-4 items-center">
              <div class="flex space-x-2">
                <a href="{{ route('edit-chore', $chore->id) }}" class="px-4 py-2 bg-selective-yellow text-white rounded hover:bg-selective-yellow-400">Edit</a>
                <button wire:click="deleteChore({{ $chore->id }})" wire:confirm="Are you sure you want to delete this chore?" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-800">Delete</button>
              </div>

              @if($pivot && !$pivot->performed)
                <button wire:click="markAsDone({{ $chore->id }})" class="px-4 py-2  bg-apple-green text-white rounded hover:bg-apple-green-400">
                  Mark as Done
                </button>
              @elseif($pivot && $pivot->performed && !$pivot->confirmed)
                <p class="text-yellow-600 font-semibold">Waiting for adult confirmation...</p>
              @elseif($pivot && $pivot->confirmed)
                <p class="text-green-600 font-semibold">Task confirmed completed!</p>
              @endif
            </div>
          @else
            @if(!$pivot || !$pivot->performed)
              <button wire:click="markAsDone({{ $chore->id }})" class="mt-3 px-4 py-2 bg-apple-green text-white rounded hover:bg-apple-green-400">
                Mark as Done
              </button>
            @elseif($pivot && $pivot->performed && !$pivot->confirmed)
              <p class="mt-2 text-selective-yellow font-semibold">Waiting for adult confirmation...</p>
            @elseif($pivot && $pivot->confirmed)
              <p class="mt-2 text-apple-green font-semibold">Task confirmed completed!</p>
            @endif
          @endif
        </li>
      @empty
        <li class="text-gray-600">No chores found.</li>
      @endforelse
    </ul>
  </div>

  <!-- User summary box -->
  <div class="basis-[40%] min-w-[300px] bg-white rounded-xl shadow p-6 flex flex-col">
    <h2 class="text-2xl font-bold mb-4">Hello, {{ auth()->user()->name }}</h2>
    <p class="mb-4">Total points earned: <span class="font-semibold">{{ $totalPoints }}</span></p>

        @if($isAdult)
      <div class="flex justify-end mb-6">
        <a href="{{ url('/chores') }}" class="inline-block px-5 py-2 bg-apple-green text-white text-sm font-medium rounded-md shadow-sm hover:bg-tangelo transition-all duration-200">+ Create Chore</a>
      </div>
    @endif

    <div class="bg-gray-100 p-4 rounded-xl shadow flex-grow overflow-y-auto max-h-[600px]">
@if(count($completedChores))
  <h3 class="text-xl font-semibold mb-4">Recently Completed Chores</h3>
  <ul class="space-y-3 mb-6">
    @foreach ($completedChores as $done)
      <li class="p-3 bg-white rounded-xl shadow flex justify-between items-center">
        <span>{{ $done->name }}</span>
        <span class="font-semibold">+{{ $done->points }} pts</span>
      </li>
    @endforeach
  </ul>
@endif


@if(count($bonusTasks))
  <h3 class="text-xl font-semibold mb-4">Bonus Tasks Available</h3>
  <ul class="space-y-3 mb-6">
    @foreach($bonusTasks as $bonus)
      <li class="p-3 bg-white rounded-xl shadow flex justify-between items-center">
        <div>
          <span>{{ $bonus->name }}</span>
          <span class="ml-2 text-sm text-gray-600">(+{{ $bonus->points }} pts)</span>
        </div>
        <button wire:click="claimBonusTask({{ $bonus->id }})" class="px-3 py-1 bg-picton-blue text-white rounded hover:bg-picton-blue-400">
          Claim
        </button>
      </li>
    @endforeach
  </ul>
@endif

@if($isAdult)
  <h3 class="text-xl font-semibold mb-4">Pending Confirmations</h3>
  @if(count($pendingConfirmations))
    <ul class="space-y-3">
      @foreach($pendingConfirmations as $pending)
        <li class="p-3 bg-white rounded-xl shadow flex justify-between items-center">
          <div>
            <span class="font-semibold">{{ $pending->user_name }}</span> completed:
            <div class="font-semibold text-sm">{{ $pending->task_name }}</div>
          </div>
          <button wire:click="confirmCompletion({{ $pending->task_id }}, {{ $pending->user_id }})" class="px-3 py-1 bg-apple-green text-white rounded hover:bg-apple-green-400">Confirm</button>
        </li>
      @endforeach
    </ul>
  @else
    <p>No pending confirmations.</p>
  @endif
@endif

    </div>
  </div>
</div>
