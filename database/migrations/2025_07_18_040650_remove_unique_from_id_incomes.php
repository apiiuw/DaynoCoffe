<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropUnique('incomes_id_incomes_unique'); // menghapus constraint unique
        });
    }

    public function down()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->unique('id_incomes'); // menambahkan kembali constraint unique jika rollback
        });
    }

};
