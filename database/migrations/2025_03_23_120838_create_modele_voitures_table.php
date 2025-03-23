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
        Schema::create('modele_voitures', function (Blueprint $table) {
            $table->id();
            $table->string('modele');
            $table->foreignId('marque_id')->constrained('marque_voitures')->onDelete('cascade'); // Foreign key to 'villes' table
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modele_voitures');
    }
};