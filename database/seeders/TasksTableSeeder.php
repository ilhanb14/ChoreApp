<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $tasks = [
            [
                'id' => 1,
                'name' => 'Cut grass',
                'family_id' => 2,
                'points' => 50,
                'recurring' => true,
                'frequency' => 'monthly',
                'start_date' => $now,
                'deadline' => $now->copy()->addMonth(),
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 2,
                'description' => 'Cut grass on the lawn',
            ],
            [
                'id' => 2,
                'name' => 'Walk the dog',
                'family_id' => 2,
                'points' => 15,
                'recurring' => true,
                'frequency' => 'daily',
                'start_date' => $now,
                'deadline' => $now->copy()->addDay(),
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 2,
                'description' => 'Walk the dog',
            ],
            [
                'id' => 3,
                'name' => 'Wash the car',
                'family_id' => 2,
                'points' => 75,
                'recurring' => false,
                'frequency' => null,
                'start_date' => $now,
                'deadline' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 4,
                'description' => 'Wash the car',
            ],
            [
                'id' => 4,
                'name' => 'Taxes',
                'family_id' => 2,
                'points' => 0,
                'recurring' => false,
                'frequency' => null,
                'start_date' => $now,
                'deadline' => $now->copy()->addMonth(),
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 2,
                'description' => 'Pay taxes',
            ],
            [
                'id' => 5,
                'name' => 'Dishes',
                'family_id' => 1,
                'points' => 25,
                'recurring' => true,
                'frequency' => 'daily',
                'start_date' => $now,
                'deadline' => $now->copy()->addDay(),
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 1,
                'description' => 'Wash the dishes',
            ],
        ];

        $task_user = [
            [
                'user_id' => 5,
                'task_id' => 3,
                'performed' => $now,
                'comment' => "Good job :) - Mom",
                'assigned_by' => 4,
                'confirmed' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 5,
                'task_id' => 2,
                'performed' => $now,
                'comment' => null,
                'assigned_by' => 5,
                'confirmed' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 4,
                'task_id' => 4,
                'performed' => null,
                'comment' => null,
                'assigned_by' => 2,
                'confirmed' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 3,
                'task_id' => 5,
                'performed' => $now,
                'comment' => null,
                'assigned_by' => 3,
                'confirmed' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        
        DB::table('tasks')->insert($tasks);
        DB::table('task_user')->insert($task_user);
    }
}
