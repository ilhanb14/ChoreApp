<?php

namespace App\Livewire\Invites;

use Livewire\Component;
use App\Models\Invite;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $invites;

    public function mount()
    {
        $this->loadInvites();
    }

    public function loadInvites()
    {
        $this->invites = Auth::user()
            ->invites()
            ->with(['family', 'inviter'])
            ->pending()
            ->get();
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
