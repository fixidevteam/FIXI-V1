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
        Schema::table('garages', function (Blueprint $table) {
            $table->string('presentation',1000)->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garages', function (Blueprint $table) {
            $table->dropColumn('presentation');
            $table->dropColumn('instagram');
            $table->dropColumn('facebook');
            $table->dropColumn('tiktok');
            $table->dropColumn('linkedin');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
};
