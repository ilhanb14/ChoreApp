<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\Family;
use App\Models\User;

class RewardsController extends Controller
{
    public function getRewardsView(Request $request) {
        // Temp hardcoded user
        $user = User::find(3);

        // Use family id in request or default to user's first family
        $familyId = $request->query('family_id') ?? $user->families->first()?->id;
        // TODO: Redirect if user has no families yet (middleware?)

        // Only allow users in the family
        abort_unless($user->families->contains($familyId), 403);

        // Get family relation for pivot data
        $userFamilyPivot = $user->families()->where('family_id', $familyId)->first();
        $points = $userFamilyPivot->pivot->points;
        $role = $userFamilyPivot->pivot->role;
        
        $activeFamily = Family::find($familyId);
        $rewards = $activeFamily->rewards;

        return view('rewards', [
            'rewards' => $rewards,
            'activeFamily' => $activeFamily,
            'families' => $user->families,
            'user' => $user,
            'points' => $points,
            'familyRole' => $role,
        ]);
    }
}
