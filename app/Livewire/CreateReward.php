<?php

namespace App\Livewire;

use Livewire\Component;
use App\Enums\ClaimType;
use App\Enums\FamilyRole;
use App\Models\Reward;

class CreateReward extends Component
{
    public $name = '';
    public $points = 1;
    public $family = 0;
    public $claimType = '0';

    public $claimTypesString = '';
    public $validFamilies;

    public function mount() {
        // Get all claim type values like "single,per_user,repeat" for validation
        $this->claimTypesString = join(',', array_column(ClaimType::cases(), 'value'));
        // Get all families where current user is an adult, 
        $this->validFamilies = auth()->user()->families()->wherePivot('role', FamilyRole::Adult->value)->get();
    }

    public function saveReward() {
        $this->validate([
            'name' => 'required|min:2',
            'points' => 'required|integer|min:1',
            'family' => 'required|exists:families,id',
            'claimType' => 'required|in:'.$this->claimTypesString,
        ]);

        // Check that user is in this family as an adult
        if (!$this->validFamilies->contains('id', $this->family)) {
            throw ValidationException::withMessages([
                'family' => 'User is not an adult in this family',
            ]);
        }

        Reward::create([
            'reward' => $this->name,
            'points' => $this->points,
            'family_id' => $this->family,
            'claim_type' => $this->claimType    
        ]);

        $this->resetExcept(['claimTypesString', 'validFamilies']);
        return redirect(request()->header('Referer'));  // Redirect to same page to refresh list of rewards
    }

    public function render()
    {
        return view('livewire.create-reward');
    }
}
