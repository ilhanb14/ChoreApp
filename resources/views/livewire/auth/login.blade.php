<div class="h-full flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/20">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold mb-2 text-tangelo">
                Sign in to your account
            </h2>
        </div>
        
        <form wire:submit.prevent="login" class="space-y-6">
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">Email Address</label>
                <input 
                    type="email" 
                    wire:model="email" 
                    placeholder="Enter your email"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-picton-blue focus:outline-none bg-white/80 transition-all duration-300 focus:transform focus:-translate-y-1 focus:shadow-lg"
                >
                @error('email') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">Password</label>
                <input 
                    type="password" 
                    wire:model="password" 
                    placeholder="Enter your password"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-picton-blue focus:outline-none bg-white/80 transition-all duration-300 focus:transform focus:-translate-y-1 focus:shadow-lg"
                >
                @error('password') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" wire:model="remember" class="w-4 h-4 rounded"> 
                    Remember me
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full py-3 px-6 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl"
            >
                Sign In
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-800 hover:underline transition-colors">
                Don't have an account? Register
            </a>
        </div>
    </div>
</div>