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
        // Change constraints on family_user pivot so that the relation will be dropped when eith the user or family is deleted
        Schema::table('family_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);   // Drop existing constraints that don't have cascade
            $table->dropForeign(['family_id']);
            
            // Add new constraints
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
                
            $table->foreign('family_id')
                ->references('id')
                ->on('families')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);   // Drop the new constraints
            $table->dropForeign(['family_id']);
            
            // Remake old constraints without cascade
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
                
            $table->foreign('family_id')
                ->references('id')
                ->on('families');
        });
    }
};
