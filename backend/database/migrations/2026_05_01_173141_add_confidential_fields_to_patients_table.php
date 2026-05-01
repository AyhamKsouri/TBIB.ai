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
        Schema::table('patients', function (Blueprint $table) {
            $table->text('persistent_sickness')->nullable(); // Encrypted via model cast
            $table->text('allergies')->nullable();          // Encrypted via model cast
            $table->text('current_treatments')->nullable();   // Encrypted via model cast
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['persistent_sickness', 'allergies', 'current_treatments']);
        });
    }
};
