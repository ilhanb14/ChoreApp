<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\ClaimType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('reward');
            $table->bigInteger('points');
            $table->bigInteger('family_id');
            $table->foreign('family_id')->references('id')->on('families');
            $table->enum('claim_type', array_column(ClaimType::cases(), 'value'));
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
