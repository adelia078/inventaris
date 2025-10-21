<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'pemeliharaan';

    protected $fillable = [
        'barang_id',
        'tanggal_rusak',
        'kerusakan',
        'penanggung_jawab',
        'biaya_perbaikan',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_rusak' => 'date',
        'biaya_perbaikan' => 'decimal:2'
    ];

    // Relasi ke model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Mendapatkan warna badge berdasarkan status
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'Rusak' => 'danger',
            'Dalam Perbaikan' => 'warning',
            'Selesai' => 'success',
            'Tidak Bisa Diperbaiki' => 'secondary',
            default => 'primary'
        };
    }
}