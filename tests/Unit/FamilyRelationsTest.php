<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Family;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FamilyRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_family_hasmany(): void
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
}
