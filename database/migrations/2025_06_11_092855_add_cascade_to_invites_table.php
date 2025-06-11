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
        // Delete invite when related users deleted
        Schema::table('invites', function (Blueprint $table) {
            $table->dropForeign(['inviter_id']);
            $table->dropForeign(['invited_id']);

            $table->foreign('inviter_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('invited_id')
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
        Schema::table('invites', function (Blueprint $table) {
            $table->dropForeign(['inviter_id']);
            $table->dropForeign(['invited_id']);

            $table->foreign('inviter_id')
                ->references('id')
                ->on('users');
            $table->foreign('invited_id')
                ->references('id')
                ->on('users');
        });
    }
};
