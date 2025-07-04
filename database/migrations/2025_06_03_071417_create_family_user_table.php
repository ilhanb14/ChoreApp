<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\FamilyRole;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('family_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained('families');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('role', array_column(FamilyRole::cases(), 'value'))->default(FamilyRole::Child->value);
            $table->bigInteger('points');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_user');
    }
};
