<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RewardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rewards = [
            [
                'id' => 1,
                'reward' => 'Ice cream',
                'points' => 50,
                'family_id' => 2,
                'claim_type' => 'repeat',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'reward' => 'Disney land',
                'points' => 800,
                'family_id' => 2,
                'claim_type' => 'single',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'reward' => 'Candy',
                'points' => 15,
                'family_id' => 1,
                'claim_type' => 'repeat',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'reward' => 'Switch 2',
                'points' => 1000,
                'family_id' => 2,
                'claim_type' => 'per_user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $reward_user = [
            [
                'reward_id' => 3,
                'user_id' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'reward_id' => 3,
                'user_id' => 3,
                'created_at' => $now->copy()->subDay(),
                'updated_at' => $now->copy()->subDay(),
            ],
            [
                'reward_id' => 4,
                'user_id' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('rewards')->insert($rewards);
        DB::table('reward_user')->insert($reward_user);
    }
}
