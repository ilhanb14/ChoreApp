<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\InviteStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('inviter_id');
            $table->foreign('inviter_id')->references('id')->on('users');
            $table->bigInteger('invited_id');
            $table->foreign('invited_id')->references('id')->on('users');
            $table->bigInteger('family_id');
            $table->enum('status', array_column(InviteStatus::cases(), 'value'))->default(InviteStatus::Pending->value);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
