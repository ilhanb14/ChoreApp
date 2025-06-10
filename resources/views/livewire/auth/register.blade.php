<div class="h-full flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8 border border-white/20">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold mb-2 text-tangelo">
                Create your new account
            </h2>
        </div>
        
        <form wire:submit.prevent="register" class="space-y-6">
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">Full Name</label>
                <input 
                    type="text" 
                    wire:model="name" 
                    placeholder="Enter your full name"
                    class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-400 focus:outline-none bg-white/80"
                >
                @error('name') 
                    <span class="text-red-500 text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">Email Address</label>
                <input 
                    type="email" 
                    wire:model="email" 
                    placeholder="Enter your email"
                    class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-400 focus:outline-none bg-white/80"
                >
                @error('email') 
                    <span class="text-red-500 text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </span> 
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Password</label>
                    <input 
                        type="password" 
                        wire:model="password" 
                        placeholder="Create password"
                        class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-400 focus:outline-none bg-white/80"
                    >
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Confirm Password</label>
                    <input 
                        type="password" 
                        wire:model="password_confirmation" 
                        placeholder="Confirm password"
                        class="input-focus w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-400 focus:outline-none bg-white/80"
                    >
                </div>
            </div>
            @error('password') 
                <span class="text-red-500 text-sm flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $message }}
                </span> 
            @enderror

            <button 
                type="submit" 
                class="w-full py-3 px-6 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl"
            >
                Create account
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800 hover:underline transition-colors">
                Already have an account? Sign in
            </a>
        </div>     
    </div>
</div>