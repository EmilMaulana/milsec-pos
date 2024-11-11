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
        Schema::create('cashflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel users
            $table->foreignId('store_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel stores
            $table->decimal('starting_balance', 15, 2); // Saldo awal sebelum transaksi
            $table->decimal('amount', 15, 2); // Jumlah uang dalam transaksi
            $table->enum('type', ['income', 'expense']); // Tipe transaksi (pemasukan atau pengeluaran)
            $table->decimal('ending_balance', 15, 2); // Saldo akhir setelah transaksi
            $table->string('description')->nullable(); // Deskripsi transaksi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashflows');
    }
};
