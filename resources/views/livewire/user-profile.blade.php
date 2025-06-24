<div>
    <x-layouts.app title="User Profile">
        <div class="h-full flex items-center justify-center p-4">
            <div class="w-full max-w-4xl">
                <!-- Page Header -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-apple-green-500 mb-2">
                        User Profile
                    </h1>
                    <p class="text-apple-green-500 text-lg">
                        Manage your account and family information
                    </p>
                </div>

                <!-- User Information Card -->
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6 border border-white/20 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Personal Information</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="text-lg font-medium text-gray-800">{{ $user->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-lg font-medium text-gray-800">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Family Information -->
                @if($families->isNotEmpty())
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6 border border-white/20 mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Family Memberships</h2>

                        <div class="space-y-6">
                            @foreach($families as $family)
                                @php
                                    $currentUserRole = $family->currentUserPivot->role;
                                @endphp
                                
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-xl font-semibold text-gray-800">{{ $family->name }}</h3>
                                        <span class="px-3 py-1 bg-picton-blue-100 text-picton-blue-800 rounded-full text-sm font-medium">
                                            {{ $currentUserRole }}
                                        </span>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                                    @if($currentUserRole === 'adult')
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($family->users as $member)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $member->pivot->role === 'adult' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                                {{ $member->pivot->role }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {{ $member->pivot->points }}
                                                        </td>
                                                        @if($currentUserRole === 'adult')
                                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                @if($member->id !== Auth::id())
                                                                    <button wire:click="confirmRemoveMember({{ $family->id }}, {{ $member->id }})" 
                                                                            class="text-tangelo-600 hover:text-tangelo-900">
                                                                        Remove
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Invitations Section -->
                @livewire('invites.index')

            </div>
        </div>
    </x-layouts.app>
    @if($showLeaveConfirmation)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Leave Family</h3>
                <p class="mb-6">Are you sure you want to leave the {{ $familyToLeave->name }} family?</p>
                
                <div class="flex justify-end space-x-4">
                    <button 
                        wire:click="$set('showLeaveConfirmation', false)" 
                        class="px-4 py-2 border border-gray-300 rounded-xl"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="leaveFamily" 
                        class="px-4 py-2 bg-gradient-to-r from-tangelo-600 to-tangelo-500 text-white rounded-xl"
                    >
                        Confirm Leave
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showRemoveMemberConfirmation)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Remove Member</h3>
                <p class="mb-6">Are you sure you want to remove {{ $memberToRemove?->name }} from the {{ $familyForRemoval?->name }} family?</p>
                
                <div class="flex justify-end space-x-4">
                    <button 
                        wire:click="$set('showRemoveMemberConfirmation', false)" 
                        class="px-4 py-2 border border-gray-300 rounded-xl"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="removeMember" 
                        class="px-4 py-2 bg-gradient-to-r from-tangelo-600 to-tangelo-500 text-white rounded-xl"
                    >
                        Confirm Remove
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
