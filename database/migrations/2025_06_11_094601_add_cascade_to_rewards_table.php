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
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropForeign(['family_id']);
            
            $table->foreign('family_id')
                ->references('id')
                ->on('families')
                ->onDelete('cascade');
        });

        Schema::table('reward_user', function (Blueprint $table) {
            $table->dropForeign(['reward_id']);
            $table->dropForeign(['user_id']);

            $table->foreign('reward_id')
                ->references('id')
                ->on('rewards')
                ->onDelete('cascade');
            $table->foreign('user_id')
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
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropForeign(['family_id']);
            
            $table->foreign('family_id')
                ->references('id')
                ->on('families');
        });

        Schema::table('reward_user', function (Blueprint $table) {
            $table->dropForeign(['reward_id']);
            $table->dropForeign(['user_id']);

            $table->foreign('reward_id')
                ->references('id')
                ->on('rewards');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }
};
