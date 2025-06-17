@php
    use App\Enums\ClaimType;
@endphp

<div class="bg-white shadow-lg p-4 rounded-lg w-128">
    <form wire:submit="saveReward" class="space-y-4">
    <div>
        <label class="block text-apple-green mb-1 text-xl">Reward Name</label>
        <input type="text" wire:model="name" class="w-full px-3 py-2 border border-gray-200 rounded" placeholder="Enter reward name">
        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-apple-green mb-1 text-xl">Point Cost</label>
        <input type="number" wire:model="points" class="w-full px-3 py-2 border border-gray-200 rounded" min='1'>
        @error('points') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-apple-green mb-1 text-xl">Family</label>
        <select wire:model="family" class="w-full px-3 py-2 border border-gray-200 rounded">
            <option value="0">Choose family</option>
            @foreach ($validFamilies as $family)
                <option value="{{$family->id}}"> {{$family->name}} </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-apple-green mb-1 text-xl">Claim Type</label>
        <select wire:model="claimType" class="w-full px-3 py-2 border border-gray-200 rounded">
            <option value="0">Choose type</option>
            @foreach (array_column(ClaimType::cases(), 'value') as $type)
                <option value="{{$type}}"> {{ ucwords(str_replace('_', ' ', $type)) }} </option>
            @endforeach
        </select>
    </div>

    <div class="mt-6">
            <button type="submit" class="w-full py-2 px-4 bg-apple-green hover:bg-apple-green-400 rounded text-white font-bold transition duration-200">
                Create Reward
            </button>
        </div>
    </form>
</div>
