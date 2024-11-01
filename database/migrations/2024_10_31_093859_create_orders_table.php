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
        Schema::dropIfExists('orders');
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('movie');
            $table->time('start_time');
            $table->time('end_time');
            $table->double('price');
            $table->string('auditorium');
            $table->string('voucher')->nullable();
            $table->integer('quantity');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('ticket_ids');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
