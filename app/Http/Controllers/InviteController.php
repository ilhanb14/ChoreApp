<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InviteController extends Controller
{
    public function index()
    {
        $invites = auth()->user()->invites()->pending()->get();
        return view('livewire.invites.index', compact('invites'));
    }

    public function accept(Invite $invite)
    {
        if ($invite->invited_id !== auth()->id()) {
            abort(403);
        }
        
        // Simple, direct approach
        $invite->update(['status' => 'accepted']);
        
        // Add user to
        $invite->family->members()->attach($invite->invited_id, [
            'role' => $invite->role,
            'points' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('invites.index')
            ->with('success', 'Invitation accepted successfully!');
    }

    public function decline(Invite $invite)
    {
        if ($invite->invited_id !== auth()->id()) {
            abort(403);
        }
        
        $invite->update(['status' => 'denied']);
        return back()->with('success', 'Invitation declined');
    }
}
