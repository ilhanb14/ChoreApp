<nav class="flex items-center justify-end gap-6">
    <a href="{{ url('/') }}" class="text-sm text-apple-green hover:text-tangelo hover:underline transition-colors">
        Home
    </a>

    @auth
        <a href="{{ url('/calendar') }}" class="text-sm text-apple-green hover:text-tangelo hover:underline transition-colors">
            Calendar
        </a>
        <a href="{{ url('/chores') }}" class="text-sm text-apple-green hover:text-tangelo hover:underline transition-colors">
            Add Chores
        </a>
        <a href="{{ route('user-chores') }}" class="text-sm text-apple-green hover:text-tangelo hover:underline transition-colors">
            Your Chores
        </a>
        <a href="{{ url('/rewards') }}" class="text-sm text-apple-green hover:text-tangelo hover:underline transition-colors">
            Rewards
        </a>
        <a href="{{ url('/invites') }}" class="text-sm text-apple-green hover:text-tangelo hover:underline transition-colors">
            Invites
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-apple-green hover:text-tangelo hover:underline bg-transparent border-none p-0 cursor-pointer transition-colors">
                Logout
            </button>
        </form>
    @endauth

    @guest
        <a href="{{ route('login') }}" class="text-sm text-apple-green hover:text-tangelo hover:underline transition-colors">
            Log in
        </a>

        <a href="{{ route('register') }}" class="text-sm text-apple-green hover:text-tangelo hover:underline transition-colors">
            Register
        </a>
    @endguest
</nav>