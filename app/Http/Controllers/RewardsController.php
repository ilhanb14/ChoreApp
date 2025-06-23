<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\Family;
use App\Models\User;
use App\Enums\ClaimType;
use App\Enums\FamilyRole;
use App\Mail\RewardClaimEmail;
use Illuminate\Support\Facades\Mail;

class RewardsController extends Controller
{
    public function getRewardsView(Request $request) {
        $user = auth()->user();

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

        // Add availability to disable claim button 
        foreach($rewards as $reward) {
            $reward->available = $this::rewardAvailable($reward, $user);
        }

        return view('rewards', [
            'rewards' => $rewards,
            'activeFamily' => $activeFamily,
            'families' => $user->families,
            'user' => $user,
            'points' => $points,
            'familyRole' => $role,
        ]);
    }

    public function claimReward(Request $request) {
        $validated = $request->validate([
        'reward_id' => ['required', 'exists:rewards,id'],
        'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::find($validated['user_id']);
        $reward = Reward::find($validated['reward_id']);

        // Check that user is in the family this reward is on
        if (!$user->families->contains(Family::find($reward->family_id))) {
            return back()->withErrors(['reward' => 'User not in family']);
        }
        // Check availability
        if(!$this::rewardAvailable($reward, $user)) {
            return back()->withErrors(['reward' => 'Reward already claimed']);
        }

        // Get points from family pivot
        $family = $user->families()->where('family_id', $reward->family_id)->first();
        $points = $family->pivot->points;

        if($reward->points > $points) {
            return back()->withErrors(['reward' => 'Can\'t afford']);
        }

        // Can be claimed
        // Add to pivot table that this user has claimed this reward
        $reward->usersClaimed()->attach($user->id);

        // Take points from user (points are per family so they must be updated in the pivot table)
        $user->families()->updateExistingPivot($family->id, [
            'points' => $points - $reward->points,
        ]);

        // Send notification to all adults in the family
        $this->sendRewardClaimEmails($user, $reward, $family);

        return redirect()->back()->with('success', 'Reward successfully claimed!');
    }

    protected function sendRewardClaimEmails(User $child, Reward $reward, Family $family)
    {
        // Get all adult members of the family
        $adults = $family->users()
                    ->wherePivot('role', 'adult')
                    ->get();

        foreach ($adults as $adult) {
            Mail::to($adult->email)->send(new RewardClaimEmail(
                $child,
                $reward,
                $family
            ));
        }
    }

    public function removeReward(Request $request) {
        $request->validate([
        'reward_id' => ['required', 'exists:rewards,id'],
        ]);

        $user = auth()->user();
        $reward = Reward::find($request->reward_id);

        // If user is not an adult in the family this reward belongs to
        if (!$user->families()->wherePivot('role', FamilyRole::Adult->value)->get()->contains($reward->family)) {
            return back()->withErrors(['reward', 'Unable to remove reward']);
        }

        $reward->usersClaimed()->detach();
        $reward->delete();

        return redirect()->back()->with('success', 'Reward removed');
    }

    /**
     * Check that a reward can be claimed by a user
     * (Unavailable if it's a one-time reward that has been claimed)
     */
    private function rewardAvailable(Reward $reward, User $user) {
        $claimType = ClaimType::from($reward->claim_type);
        return !(in_array($claimType, [ ClaimType::Single, ClaimType::PerUser ]) && $reward->usersClaimed->contains($user));
    }
}
