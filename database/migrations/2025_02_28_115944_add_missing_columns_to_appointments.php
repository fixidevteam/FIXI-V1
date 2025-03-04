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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('categorie_de_service')->after('garage_ref');
            $table->string('modele')->nullable()->after('categorie_de_service');
            $table->string('numero_immatriculation')->nullable()->after('modele');
            $table->string('objet_du_RDV')->nullable()->after('numero_immatriculation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'categorie_de_service',
                'modele',
                'numero_immatriculation',
                'objet_du_RDV',
            ]);
        });
    }
};