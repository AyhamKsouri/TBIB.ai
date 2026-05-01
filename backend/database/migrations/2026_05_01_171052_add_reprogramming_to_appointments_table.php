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
            $table->date('suggested_date')->nullable();
            $table->time('suggested_time')->nullable();
            $table->string('status')->default('scheduled')->change(); // scheduled, cancelled, completed, pending_repro
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['suggested_date', 'suggested_time']);
        });
    }
};
