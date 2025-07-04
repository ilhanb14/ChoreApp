<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UsersTableSeeder::class,
            FamiliesTableSeeder::class,
            TasksTableSeeder::class,
            RewardsTableSeeder::class
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([   // Needs to be called last because it uses Test User
            InvitesTableSeeder::class,
        ]);
    }
}
