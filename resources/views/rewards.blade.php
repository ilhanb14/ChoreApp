@php
    use App\Enums\ClaimType;
@endphp

@include('layouts.header')
<div class='px-14 pt-6'>
    <div class='flex justify-between w-full'>
        <h1 class='text-3xl font-bold mb-2 text-selective-yellow'> {{ $activeFamily->name }} Reward Shop </h1>
        <h3 class='text-xl font-semibold text-selective-yellow'> Points: {{ $points }}
    </div>
    <div class="flex flex-wrap gap-8 justify-start mt-4 mb-6">
    @foreach($rewards as $reward)
        <div class="bg-white shadow-lg p-4 rounded-lg w-64">
            <p class="text-apple-green-400 text-xl">{{ $reward->reward }}</p>
            <p class="text-apple-green-400 text-xl mb-1">{{ $reward->points }} Points</p>
            <p class="text-apple-green mb-4">
                {{ ucwords(str_replace('_', ' ', $reward->claim_type)) }} {{--  --}}
            </p>
            <button
                @if (!$reward->available) disabled @endif
                class="
                    {{ $reward->available && $reward->points <= $points
                        ? 'bg-selective-yellow hover:bg-selective-yellow-600 text-white border-selective-yellow-400'
                        : 'bg-gray-300 text-gray-500 border-gray-400 cursor-not-allowed'
                    }}
                    font-semibold py-2 px-4 rounded-lg border
            ">
                @if(!$reward->available)
                    Claimed
                @elseif($reward->points > $points)
                    Can't afford
                @else
                    Claim
                @endif
            </button>
        </div>
    @endforeach
    </div>

    @if(count($families) > 1)
    <h3 class="text-xl font-bold mb-2 text-selective-yellow"> Switch family </h1>
    @foreach ($families as $family)
        <a 
            href="{{ $family->id === $activeFamily->id ? '#' : route('rewards', ['family_id' => $family->id]) }}"
            class="inline-block px-4 py-2 rounded-lg text-white font-semibold shadow-lg 
                {{ $family->id === $activeFamily->id 
                    ? 'bg-gray-400 cursor-not-allowed pointer-events-none' 
                    : 'bg-apple-green hover:bg-apple-green-400 border-lg boder-apple-green-300' }}"
            {{ $family->id === $activeFamily->id ? 'aria-disabled=true' : '' }}
        >
    {{ $family->name }}
    </a>
    @endforeach
    @endif
</div>
