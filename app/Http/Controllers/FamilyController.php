<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\FamilyRole;

class FamilyController extends Controller
{
    public function create(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        $family = Family::create(['name' => $request->name]);
        $family->users()->attach(auth()->id(), ['role' => 'adult']);
        
        return redirect()->route('families.show', $family);
    }

    public function sendInvite(Request $request, Family $family)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:adult,child'
        ]);
        
        if (!$this->userCanInvite(auth()->user(), $family)) {
            abort(403, 'You cannot invite to this family');
        }
        
        $invited = User::where('email', $request->email)->first();
        
        if ($this->hasExistingInvite($family, $invited) || 
            $family->users()->where('user_id', $invited->id)->exists()) {
            return back()->with('error', 'User already has an invitation or is already a member');
        }
        
        Invite::create([
            'family_id' => $family->id,
            'inviter_id' => auth()->id(),
            'invited_id' => $invited->id,
            'role' => FamilyRole::from($request->role),
            'status' => 'pending'
        ]);
        
        return back()->with('success', 'Invite sent');
    }
    
    protected function userCanInvite(User $user, Family $family): bool
    {
        return $family->users()
            ->where('user_id', $user->id)
            ->where('role', 'adult')
            ->exists();
    }
    
    protected function hasExistingInvite(Family $family, User $invited): bool
    {
        return Invite::where('family_id', $family->id)
            ->where('invited_id', $invited->id)
            ->pending()
            ->exists();
    }
}