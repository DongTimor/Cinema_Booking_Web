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
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('tickets_showtime_id_foreign');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['showtime_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('showtime_id')->constrained('showtimes')->onDelete('cascade');
        });
    }
};
