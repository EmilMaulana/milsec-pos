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
            $table->string('receipt_id')->unique();
            $table->foreignId('phone_id')->nullable()->constrained('customer_phones')->onDelete('cascade'); // Menentukan tabel 'phones' secara eksplisit
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 15, 2);
            $table->decimal('payment_amount', 15, 2); // Tambahkan kolom payment_amount
            $table->string('payment_method'); // Tambahkan kolom payment_method
            $table->timestamp('transaction_date');
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
