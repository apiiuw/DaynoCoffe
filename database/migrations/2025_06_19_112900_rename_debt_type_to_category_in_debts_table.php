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
        Schema::table('debts', function (Blueprint $table) {
            // Tambahkan kolom baru 'category' dengan tipe data sama seperti 'debt_type'
            $table->string('category')->nullable();
        });

        // Salin data dari 'debt_type' ke 'category'
        DB::table('debts')->update([
            'category' => DB::raw('debt_type')
        ]);

        // Hapus kolom lama 'debt_type'
        Schema::table('debts', function (Blueprint $table) {
            $table->dropColumn('debt_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->string('debt_type')->nullable();
        });

        DB::table('debts')->update([
            'debt_type' => DB::raw('category')
        ]);

        Schema::table('debts', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
