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
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('showtimes', function (Blueprint $table) {
            $table->dropColumn('movie_id');
        });
    }
};
