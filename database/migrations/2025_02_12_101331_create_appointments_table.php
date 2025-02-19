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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('user_full_name');
            $table->string('user_phone');
            $table->string('user_email')->nullable();
            $table->string('garage_ref');
            $table->date('appointment_day');
            $table->time('appointment_time');
            $table->enum('status', ['en_cour', 'confirmed', 'cancelled'])->default('en_cour');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};