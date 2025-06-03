<header class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
    <div class="flex items-center justify-between px-4 py-3">
        <div class="flex items-center gap-4">
            <a href="{{ url('/') }}" class="block w-10 h-auto">
                <img src="/logo.png" alt="ChoreBusters Logo" class="w-full h-auto">
            </a>
            <div class="text-xl font-bold text-apple-green">ChoreBusters</div>
        </div>
        @include('layouts.navbar')
    </div>
</header>