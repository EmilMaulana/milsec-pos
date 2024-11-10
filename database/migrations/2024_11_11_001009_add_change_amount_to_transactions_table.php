<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangeAmountToTransactionsTable extends Migration
{
    /**
     * Menjalankan migrasi untuk menambahkan kolom change_amount.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Menambahkan kolom change_amount dengan tipe data decimal
            $table->decimal('change_amount', 10, 2)->nullable()->after('payment_amount');
        });
    }

    /**
     * Membalikkan migrasi untuk menghapus kolom change_amount.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Menghapus kolom change_amount jika migrasi dibalik
            $table->dropColumn('change_amount');
        });
    }
}
