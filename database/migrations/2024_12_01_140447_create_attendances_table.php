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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->string('status'); // 'in' atau 'out'
            $table->string('photo')->nullable(); // Untuk menyimpan path foto
            $table->string('location')->nullable(); // Untuk menyimpan lokasi
            $table->string('latitude')->nullable(); // Untuk menyimpan lokasi
            $table->string('longitude')->nullable(); // Untuk menyimpan lokasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
