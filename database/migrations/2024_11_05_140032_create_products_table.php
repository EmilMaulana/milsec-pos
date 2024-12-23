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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores', 'id')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->decimal('base_price', 10, 2);
            $table->decimal('sell_price', 10, 2);
            $table->decimal('disc', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->string('unit')->nullable(); // Menambahkan kolom unit (satuan)
            $table->softDeletes(); // Menambahkan kolom deleted_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
