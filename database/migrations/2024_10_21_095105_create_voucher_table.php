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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 6)->unique();
            $table->longText('description')->nullable();
            $table->bigInteger('quantity')->default(0);
            $table->date('expires_at')->nullable();
            $table->integer('value');
            $table->enum('type' , ['percent', 'fixed'])->default('fixed');
            $table->timestamps();
        });

        Schema::create('user_voucher', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('user_voucher');
    }
};
