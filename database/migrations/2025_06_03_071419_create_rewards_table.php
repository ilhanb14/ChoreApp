<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ClaimType;

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
            $table->foreignId('family_id')->constrained('families');
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
