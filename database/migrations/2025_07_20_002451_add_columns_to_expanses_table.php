<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToExpansesTable extends Migration
{
    /**
     * Jalankan migrasi untuk menambahkan kolom pada tabel expanses.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Menambahkan kolom id_expanses
            $table->string('id_expanses', 255)->nullable();

            // Menambahkan kolom price
            $table->decimal('price', 15, 2)->nullable();

            // Menambahkan kolom quantity
            $table->integer('quantity', false, true)->nullable();

            // Menambahkan kolom total_price
            $table->decimal('total_price', 15, 2)->nullable();
        });
    }

    /**
     * Balikkan perubahan yang dilakukan oleh migrasi ini.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Menghapus kolom yang telah ditambahkan
            $table->dropColumn(['id_expanses', 'price', 'quantity', 'total_price']);
        });
    }
}
