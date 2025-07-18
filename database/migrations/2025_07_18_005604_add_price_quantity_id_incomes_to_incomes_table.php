<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceQuantityIdIncomesToIncomesTable extends Migration
{
    /**
     * Menjalankan migrasi untuk menambah kolom baru.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incomes', function (Blueprint $table) {
            // Menambahkan kolom price untuk menyimpan harga atau nominal
            $table->decimal('price', 15, 2)->nullable()->after('category');

            // Menambahkan kolom quantity untuk menyimpan jumlah
            $table->integer('quantity')->nullable()->after('price');

            // Menambahkan kolom id_incomes untuk ID random
            $table->string('id_incomes')->unique()->after('quantity');
        });
    }

    /**
     * Membatalkan migrasi dan menghapus kolom yang baru ditambahkan.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incomes', function (Blueprint $table) {
            // Menghapus kolom price
            $table->dropColumn('price');

            // Menghapus kolom quantity
            $table->dropColumn('quantity');

            // Menghapus kolom id_incomes
            $table->dropColumn('id_incomes');
        });
    }
}
