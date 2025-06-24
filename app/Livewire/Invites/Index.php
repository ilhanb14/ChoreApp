<?php

namespace App\Livewire\Invites;

use Livewire\Component;
use App\Models\Invite;
use App\Models\Family;
use App\Models\User;
use App\Enums\FamilyRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\FamilyInvitationEmail;

class Index extends Component
{
    public $invites;
    public $families = [];
    public $email = '';
    public $family_id = '';
    public $role = FamilyRole::Child->value;
    public $familyName = '';


    public function mount()
    {
        $this->loadInvites();
        $this->loadFamiliesAdult();
    }

    public function loadFamiliesAdult()
    {
        $this->families = Auth::user()->families()
            ->wherePivot('role', FamilyRole::Adult->value)
            ->get();
    }

    public function loadInvites()
    {
        $this->invites = Auth::user()
            ->invites()
            ->with(['family', 'inviter'])
            ->pending()
            ->get();
    }

    
    public function invite()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'family_id' => 'required|exists:families,id',
            'role' => 'required|in:' . implode(',', FamilyRole::values()),
        ]);

        $invitee = User::where('email', $this->email)->first();
        $family = Family::find($this->family_id);

        // Check if user is already in the family
        if ($family->users()->where('user_id', $invitee->id)->exists()) {
            $this->addError('email', 'This user is already a member of the selected family');
            return;
        }

        // Check if pending invite already exists
        if (Invite::where('family_id', $family->id)
            ->where('invited_id', $invitee->id)
            ->pending()
            ->exists()) {
            $this->addError('email', 'A pending invitation already exists for this user');
            return;
        }

        // Create the invite
        $invite = Invite::create([
            'family_id' => $family->id,
            'inviter_id' => Auth::id(),
            'invited_id' => $invitee->id,
            'role' => $this->role,
            'status' => 'pending',
        ]);

        // Send email
        Mail::to($invitee->email)->send(new FamilyInvitationEmail(Auth::user(), $family));

        // Reset form
        $this->reset(['email', 'family_id', 'role']);
        $this->loadInvites();
        
        session()->flash('success', 'Invitation sent successfully!');
    }

    public function createFamily()
    {
        $this->validate([
            'familyName' => 'required|string|max:255',
        ]);

        $family = Family::create([
            'name' => $this->familyName,
        ]);

        // Add the creator as an adult member
        $family->members()->attach(Auth::id(), [
            'role' => FamilyRole::Adult->value,
            'points' => 0
        ]);

        // Reset form and reload data
        $this->reset(['familyName']);
        $this->loadFamiliesAdult();
        
        session()->flash('success', 'Family ' . $family->name . ' created successfully!');
    }

    public function accept($inviteId)
    {
        $invite = Invite::findOrFail($inviteId);
    
        // Verify the authenticated user is the invited user
        if (Auth::id() != $invite->invited_id) {
            return;
        }

        // Add user to family
        $invite->family->members()->attach($invite->invited_id, [
            'role' => $invite->role,
            'points' => 0
        ]);

        $invite->update(['status' => 'accepted']);

        $this->loadInvites();
        session()->flash('success', 'Invitation accepted! You are now a member of the family.');
    }

    public function decline($inviteId)
    {
        $invite = Invite::findOrFail($inviteId);

        // Verify the authenticated user is the invited user
        if (Auth::id() != $invite->invited_id) {
            return;
        }

        $invite->update(['status' => 'declined']);

        $this->loadInvites();
        session()->flash('success', 'Invitation successfully declined.');
    }

    public function render()
    {
        return view('livewire.invites.index');
    }
}
