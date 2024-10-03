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
        Schema::table('showtimes', function (Blueprint $table) {
            $table->dropColumn('movie_id');
            $table->dropForeign('showtimes_auditorium_id_foreign');
            $table->dropColumn('auditorium_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('showtimes', function (Blueprint $table) {
            $table->integer('movie_id');
            $table->foreignId('auditorium_id')->constrained('auditoriums')->onDelete('cascade');
        });
    }
};
