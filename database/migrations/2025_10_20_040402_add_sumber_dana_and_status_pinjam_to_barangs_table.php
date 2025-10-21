<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->enum('sumber_dana', ['Pemerintah', 'Swadaya', 'Donatur'])
                  ->default('Pemerintah')
                  ->after('tanggal_pengadaan');
            
            $table->boolean('boleh_dipinjam')
                  ->default(true)
                  ->after('sumber_dana');
            
            $table->enum('jenis_barang', ['Unik', 'Massal'])
                  ->default('Massal')
                  ->after('boleh_dipinjam')
                  ->comment('Unik: generate kode otomatis per unit, Massal: 1 kode untuk semua');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['sumber_dana', 'boleh_dipinjam', 'jenis_barang']);
        });
    }
};