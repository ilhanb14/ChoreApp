<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamiliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $families = [
            [
                'id' => 1,
                'name' => 'Doe Family',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Smith Family',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'Segers Family',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        $family_user = [
            [
                'family_id' => 1,
                'user_id' => 1,
                'role' => 'adult',
                'points' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'family_id' => 1,
                'user_id' => 3,
                'role' => 'child',
                'points' => 200,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'family_id' => 2,
                'user_id' => 2,
                'role' => 'adult',
                'points' => 50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'family_id' => 2,
                'user_id' => 4,
                'role' => 'adult',
                'points' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'family_id' => 2,
                'user_id' => 3,
                'role' => 'child',
                'points' => 45,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'family_id' => 2,
                'user_id' => 5,
                'role' => 'child',
                'points' => 125,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('families')->insert($families);
        DB::table('family_user')->insert($family_user);
    }
}
