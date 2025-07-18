<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->nullable();  // Menambah kolom total_price
        });
    }

    public function down()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('total_price');
        });
    }

};
