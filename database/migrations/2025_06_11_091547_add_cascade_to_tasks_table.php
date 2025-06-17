<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete task when family deleted
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['family_id']);     // Drop existing constraints that don't have cascade
            $table->dropForeign(['created_by']);

            $table->foreign('family_id')
                ->references('id')
                ->on('families')
                ->onDelete('cascade');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('task_user', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['assigned_by']);

            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('assigned_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['family_id']);

            // Remake old constraint without cascade
            $table->foreign('family_id')
                    ->references('id')
                    ->on('families');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropForeign(['user_id']);

            $table->foreign('task_id')
                    ->references('id')
                    ->on('tasks');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
        });
    }
};
