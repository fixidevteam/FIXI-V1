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
        Schema::create('reference_techniques', function (Blueprint $table) {
            $table->id();
            $table->string('reference_technique')->nullable();
            $table->string('motorisation')->nullable();
            $table->string('boite_vitesse')->nullable();
            $table->integer('puissance_thermique')->nullable();
            $table->integer('puissance_fiscale')->nullable();
            $table->float('cylindree')->nullable();
            $table->foreignId('modele_id')->constrained('modele_voitures')->onDelete('cascade'); // Foreign key to 'villes' table
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_techniques');
    }
};