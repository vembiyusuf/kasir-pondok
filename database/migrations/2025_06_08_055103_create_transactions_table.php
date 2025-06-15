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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code')->unique();
            $table->foreignId('user_id')->constrained();
            $table->enum('payment_method', ['cash', 'card', 'transfer']);
            $table->integer('subtotal_amount');
            $table->integer('discount_amount')->default(0);
            $table->integer('total_amount');
            $table->integer('amount_paid');
            $table->integer('change');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
