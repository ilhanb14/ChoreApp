<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Family;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Component
{
    public User $user;
    public $editing = false;
    public $name;
    public $password;
    public $password_confirmation;
    public $families = [];
    
    // For leave family confirmation
    public $showLeaveConfirmation = false;
    public $familyToLeave;
    
    // For remove member confirmation
    public $showRemoveMemberConfirmation = false;
    public $familyForRemoval;
    public $memberToRemove;

    protected $rules = [
        'name' => 'required|string|max:255',
        'password' => 'nullable|confirmed|min:8',
    ];

    public function mount($userId = null)
    {
        $this->user = $userId ? User::findOrFail($userId) : Auth::user();
        $this->name = $this->user->name;
        $this->loadFamilies();
    }

    public function loadFamilies()
    {
        $this->families = $this->user->families()
            ->with(['users' => function($query) {
                $query->withPivot(['role', 'points']);
            }])
            ->get()
            ->map(function($family) {
                // Manually add pivot data for the current user
                $family->currentUserPivot = $family->users->find($this->user->id)->pivot;
                return $family;
            });
    }

    public function toggleEdit()
    {
        $this->editing = !$this->editing;
    }

    public function save()
    {
        $this->validate();

        $this->user->name = $this->name;
        
        if ($this->password) {
            $this->user->password = bcrypt($this->password);
        }

        $this->user->save();
        $this->editing = false;
        
        session()->flash('message', 'Profile updated successfully.');
    }

    public function confirmLeaveFamily($familyId)
    {
        $this->familyToLeave = Family::find($familyId);
        $this->showLeaveConfirmation = true;
    }

    public function leaveFamily()
    {
        $family = $this->familyToLeave;
        $user = Auth::user();
        
        // Get all adult members
        $adults = $family->users()
            ->wherePivot('role', 'adult')
            ->orderBy('family_user.created_at', 'asc')
            ->get();
            
        // If user is the last adult and there are other members
        if ($adults->count() === 1 && $adults->first()->id === $user->id && $family->users->count() > 1) {
            // Find the oldest non-adult member and promote them
            $newAdult = $family->users()
                ->where('users.id', '!=', $user->id)
                ->orderBy('family_user.created_at', 'asc')
                ->first();
                
            if ($newAdult) {
                $family->members()->updateExistingPivot($newAdult->id, [
                    'role' => 'adult'
                ]);
            }
        }
        
        // Remove the user from the family
        $family->members()->detach($user->id);
        
        // If this was the last member, delete the family
        if ($family->users()->count() === 0) {
            $family->delete();
        }
        
        $this->showLeaveConfirmation = false;
        $this->loadFamilies();
        session()->flash('message', 'You have left the family successfully.');
    }

    public function confirmRemoveMember($familyId, $memberId)
    {
        $this->familyForRemoval = Family::find($familyId);
        $this->memberToRemove = User::find($memberId);
        $this->showRemoveMemberConfirmation = true;
    }

    public function removeMember()
    {
        $family = $this->familyForRemoval;
        $member = $this->memberToRemove;
        $currentUser = Auth::user();
        
        // Check if the current user is an adult in this family
        $isAdult = $family->users()
            ->where('user_id', $currentUser->id)
            ->wherePivot('role', 'adult')
            ->exists();
            
        if (!$isAdult) {
            $this->showRemoveMemberConfirmation = false;
            session()->flash('error', 'You do not have permission to remove members.');
            return;
        }
        
        // Remove the member
        $family->members()->detach($member->id);
        
        $this->showRemoveMemberConfirmation = false;
        $this->loadFamilies();
        session()->flash('message', 'Member removed successfully.');
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}