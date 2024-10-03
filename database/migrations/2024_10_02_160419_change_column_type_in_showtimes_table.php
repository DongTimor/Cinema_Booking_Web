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
            $table->dropForeign('showtimes_movie_id_foreign');
        });

        Schema::table('showtimes', function (Blueprint $table) {
            $table->time('start_time')->change();
            $table->time('end_time')->change();
            $table->integer('movie_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('showtimes', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
        });

        Schema::table('showtimes', function (Blueprint $table) {
            $table->date('start_time')->change();
            $table->date('end_time')->change();
            $table->dropColumn('movie_id');
        });
    }
};
