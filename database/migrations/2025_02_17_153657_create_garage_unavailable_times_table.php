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
        Schema::create('garage_unavailable_times', function (Blueprint $table) {
            $table->id();
            $table->string('garage_ref'); // Reference to the garage
            $table->integer('unavailable_day'); // 0 = Sunday, 1 = Monday, etc.
            $table->time('unavailable_from'); // Start time of unavailability
            $table->time('unavailable_to'); // End time of unavailability
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garage_unavailable_times');
    }
};