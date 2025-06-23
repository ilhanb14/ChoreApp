<div>
    <div class="h-full flex items-center justify-center p-4">
        <div class="w-full max-w-4xl">
            <!-- Page Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-apple-green-500 mb-2">
                    Your Invitations
                </h1>
                <p class="text-apple-green-500 text-lg">
                    Manage your family invitations
                </p>
            </div>

            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-apple-green-100 text-apple-green-800 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-tangelo-100 text-tangelo-800 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Invitation Form (Always Visible) -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6 border border-white/20 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Invite New Member</h2>
                
                <form wire:submit.prevent="invite" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Email Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input 
                                wire:model="email" 
                                type="email" 
                                placeholder="Enter email"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-picton-blue focus:outline-none bg-white/80 transition-all duration-300"
                            >
                            @error('email') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Family Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Family</label>
                            <select 
                                wire:model="family_id" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-picton-blue focus:outline-none bg-white/80 transition-all duration-300"
                            >
                                <option value="">Select Family</option>
                                @foreach($families as $family)
                                    <option value="{{ $family->id }}">{{ $family->name }}</option>
                                @endforeach
                            </select>
                            @error('family_id') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select 
                                wire:model="role" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-picton-blue focus:outline-none bg-white/80 transition-all duration-300"
                            >
                                @foreach(\App\Enums\FamilyRole::cases() as $role)
                                    <option value="{{ $role->value }}">{{ $role->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full md:w-auto py-3 px-6 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl"
                        >
                            Send Invitation
                        </button>
                    </div>
                </form>
            </div>

            @if($invites->isEmpty())
                <!-- Empty State -->
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-12 border border-white/20 text-center">
                    <div class="mb-6">
                        <svg class="w-16 h-16 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m13-8l-4 4m0 0l-4-4m4 4V3"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No pending invitations</h3>
                    <p class="text-gray-500 mb-6">You don't have any family invitations at the moment.</p>
                    <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-tangelo to-tangelo-600 hover:from-tangelo-600 hover:to-tangelo-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Home
                    </a>
                </div>
            @else
                <!-- Summary Stats -->
                <div class="mt-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/20 p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-tangelo/10 p-3 rounded-xl mr-4">
                                <svg class="w-6 h-6 text-tangelo" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Pending Invitations</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $invites->count() }}</p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-picton-blue-900 hover:bg-picton-blue-800 text-picton-blue rounded-lg transition-colors font-medium text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Home
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Invitations Grid -->
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($invites as $invite)
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-3xl">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-picton-blue to-picton-blue-600 p-6 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium opacity-90">Family Invitation</span>
                                </div>
                                <span class="px-2 py-1 bg-white/20 rounded-full text-xs font-medium">
                                    {{ $invite->role }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold truncate">{{ $invite->family->name }}</h3>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-sm">Sent by: <span class="font-medium">{{ $invite->inviter->name }}</span></span>
                                </div>
                                
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm">{{ $invite->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <button wire:click="accept({{ $invite->id }})" 
                                        class="w-full py-3 px-4 bg-gradient-to-r from-apple-green-600 to-apple-green-500 hover:from-apple-green-500 hover:to-apple-green-300 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl">
                                    Accept
                                </button>
                                
                                <button wire:click="decline({{ $invite->id }})" 
                                        class="w-full py-3 px-4 bg-gradient-to-r from-tangelo-600 to-tangelo-500 hover:from-tangelo-500 hover:to-tangelo-400 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:transform hover:-translate-y-1 hover:shadow-xl">
                                    Decline
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div
