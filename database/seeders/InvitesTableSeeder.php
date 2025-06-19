<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class InvitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get id of Test User
        $testUserId = User::where('name', 'Test User')->first()->id;

        $invites = [
            [
                'id' => 1,
                'inviter_id' => 1,
                'invited_id' => 3,
                'family_id' => 1,
                'role' => 'child',
                'status' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'inviter_id' => 1,
                'invited_id' => $testUserId,
                'family_id' => 1,
                'role' => 'adult',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'inviter_id' => 2,
                'invited_id' => $testUserId,
                'family_id' => 2,
                'role' => 'child',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('invites')->insert($invites);
    }
}
