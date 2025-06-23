@include('layouts.header')

<div class='flex items-center justify-center p-16 flex-col gap-8'>
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-48 w-auto">
        </div>
        
        <!-- Content -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-apple-green mb-2">ChoreBusters</h1>
            
            <div class="text-left space-y-4 mb-6">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-tangelo rounded-full mt-2"></div>
                    <p class="text-gray-700">Turn household tasks into exciting challenges</p>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-selective-yellow rounded-full mt-2"></div>
                    <p class="text-gray-700">Earn points for completing chores</p>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-picton-blue rounded-full mt-2"></div>
                    <p class="text-gray-700">Track progress and celebrate achievements</p>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-apple-green rounded-full mt-2"></div>
                    <p class="text-gray-700">Build responsibility and teamwork</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-3xl w-full">
        @guest
        <h1 class="text-2xl font-bold text-apple-green mb-2">You are not logged in</h1>

        <a  href="{{ route('login') }}" class='inline-block px-4 py-2 rounded-lg text-white font-semibold shadow-lg mb-2 mx-4 bg-selective-yellow hover:bg-selective-yellow-600'>
            Log in
        </a>
        <a  href="{{ route('register') }}" class='inline-block px-4 py-2 rounded-lg text-white font-semibold shadow-lg mb-2 bg-selective-yellow hover:bg-selective-yellow-600'>
        Register
        </a>
        @endguest

        @auth
        @if($recommendedChore)
            <h1 class="text-2xl font-bold text-apple-green mb-2">Recommended chore</h1>

            <a href="{{ url('/my-chores') }}" class='text-xl font-semibold text-selective-yellow hover:underline'> {{ $recommendedChore->name }} </a>
            <p class='text-xl text-gray-700'>
                <span class='font-semibold'> Description: </span> {{ $recommendedChore->description ?? "None" }} <br>
                <span class='font-semibold'> Deadline: </span> {{ \Carbon\Carbon::parse($recommendedChore->deadline)->format('d/m/Y') }} <br>
                <span class='font-semibold'> Points: </span> {{ $recommendedChore->points }}
            </p>
        @else
            <h1 class="text-2xl font-bold text-apple-green mb-2">Recommended chore</h1>
            <p class='text-xl text-gray-700 mb-2'>
                Right now, you have no chores on a deadline
            </p>
            <a href="{{ url('/my-chores') }}" class='text-xl font-semibold text-selective-yellow hover:underline'> See all your chores </a> <br>
            <a href="{{ url('/calendar') }}" class='text-xl font-semibold text-selective-yellow hover:underline'> Check your calendar </a>

        @endif
        @endauth
    </div>
</div>

@include('layouts.footer')
