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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., KBZPay, WavePay
            $table->string('photo')->nullable(); // Path to the QR code image
            $table->string('phone')->nullable(); // Account phone number
            $table->boolean('status')->default(true); // Active or Inactive
            $table->text('remark')->nullable(); // Instructions like "Send as Gift"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
