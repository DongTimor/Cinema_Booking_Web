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
        Schema::dropIfExists('auditorium_movie');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('auditorium_movie', function (Blueprint $table) {
            $table->foreignId('auditorium_id')->constrained('auditoriums')->onDelete('cascade');
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
        });
    }
};
