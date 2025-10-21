<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeliharaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->date('tanggal_rusak');
            $table->text('kerusakan');
            $table->string('penanggung_jawab');
            $table->decimal('biaya_perbaikan', 12, 2);
            $table->enum('status', ['Rusak', 'Dalam Perbaikan', 'Selesai', 'Tidak Bisa Diperbaiki'])->default('Rusak');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeliharaan');
    }
};