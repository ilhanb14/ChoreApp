<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Reward;
use App\Models\Family;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RewardsRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_rewards_belongsto(): void
    {
        // Create test family
        $family = Family::create([
            'name' => 'Test Family',
        ]);
        
        // Create test reward
        $reward = Reward::create([
            'reward' => 'Reward',
            'points' => 10,
            'family_id' => $family->id,
            'claim_type' => 'single'
        ]);

        // Assert class of relation
        $this->assertInstanceOf(Family::class, $reward->family);
        // Assert reward belongs to family
        $this->assertEquals($reward->family->id, $family->id);
    }

    public function test_family_hasmany_rewards(): void
    {
        // Create test family
        $family = Family::create([
            'name' => 'Test Family',
        ]);
        
        // Create test reward
        $reward1 = Reward::create([
            'reward' => 'Reward1',
            'points' => 10,
            'family_id' => $family->id,
            'claim_type' => 'single'
        ]);
        $reward2 = Reward::create([
            'reward' => 'Reward2',
            'points' => 20,
            'family_id' => $family->id,
            'claim_type' => 'repeat'
        ]);

        // Assert amount of rewards
        $this->assertCount(2, $family->rewards);

        // Assert each user in rewards
        $this->assertTrue($family->rewards->contains($reward1));
        $this->assertTrue($family->rewards->contains($reward2));
    }
}
