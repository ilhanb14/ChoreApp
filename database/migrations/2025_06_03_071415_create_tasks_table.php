<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TaskFrequency;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('family_id');
<<<<<<< HEAD
            $table->foreignId('family_id')->constrained('families');
=======
            $table->foreign('family_id')->references('id')->on('families');
>>>>>>> 0e7dc06c05f76cf0b0ce2fc0d75671955b20f0b8
            $table->integer('points');
            $table->boolean('recurring')->default(0);
            $table->enum('frequency', array_column(TaskFrequency::cases(), 'value'))->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
