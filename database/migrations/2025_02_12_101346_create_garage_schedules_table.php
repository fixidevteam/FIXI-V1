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
        Schema::create('garage_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('garage_ref');
            $table->integer('available_day'); // 0 = Sunday, 1 = Monday, etc.
            $table->time('available_from');
            $table->time('available_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garage_schedules');
    }
};