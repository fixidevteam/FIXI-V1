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
        Schema::table('operations', function (Blueprint $table) {
            // Make existing columns nullable
            $table->unsignedBigInteger('voiture_id')->nullable()->change();
            $table->date('date_operation')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            // Revert changes
            $table->unsignedBigInteger('voiture_id')->nullable(false)->change();
            $table->date('date_operation')->nullable(false)->change();
        });
    }
};
