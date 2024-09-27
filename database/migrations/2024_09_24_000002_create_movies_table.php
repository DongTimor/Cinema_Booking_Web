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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->text('description');
            $table->string('trailer', 255);
            $table->dateTime('start_time')->nullable(false);
            $table->dateTime('end_time');
            $table->integer('duration')->nullable(false);
            $table->enum('status',['pending','completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
