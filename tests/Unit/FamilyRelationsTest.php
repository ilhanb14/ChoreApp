<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Family;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FamilyRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_family_members(): void
    {
        // Create test family
        $family = Family::create([
            'name' => 'Test Family',
        ]);

        // Create test users and add as family members with pivot
        $adult = User::factory()->create();
        $child = User::factory()->create();
        $family->members()->attach([
            $adult->id => ['role' => 'adult', 'points' => 0],
            $child->id => ['role' => 'child', 'points' => 50],
        ]);

        $family->refresh();

        // Assert amount of members
        $this->assertCount(2, $family->members);

        // Assert each user in members
        $this->assertTrue($family->members->contains($adult));
        $this->assertTrue($family->members->contains($child));
    }

    public function test_user_families(): void
    {
        // Test user
        $user = User::factory()->create();

        // Create test families
        $family1 = Family::create([
            'name' => 'Test Family',
        ]);
        $family2 = Family::create([
            'name' => 'Test Family',
        ]);

        $user->families()->attach([
            $family1->id => ['role' => 'adult', 'points' => 25],
            $family2->id => ['role' => 'adult', 'points' => 0],
        ]);

        // Assert user has 2 families
        $this->assertCount(2, $user->families);

        // Assert user has both families
        $this->assertTrue($user->families->contains($family1));
        $this->assertTrue($user->families->contains($family2));

        // Assert pivot has points
        $this->assertEquals($user->families->first()->pivot->points, 25);
    }
}
