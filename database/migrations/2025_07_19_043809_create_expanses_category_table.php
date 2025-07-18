<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('expanses_category', function (Blueprint $table) {
            $table->id();
            $table->string('category');        // Kategori umum
            $table->string('item');            // Nama pengeluaran
            $table->decimal('nominal', 15, 2); // Jumlah uang
            $table->text('keterangan')->nullable(); // Deskripsi/keterangan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expanses_category');
    }
};
