<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'lokasi_id',
        'jumlah',
        'satuan',
        'kondisi',
        'tanggal_pengadaan',
        'gambar',
        'sumber_dana',
        'boleh_dipinjam',
        'jenis_barang',
    ];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
        'boleh_dipinjam' => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Relasi ke model Pemeliharaan
    public function pemeliharaan()
    {
        return $this->hasMany(Pemeliharaan::class);
    }

    // Get pemeliharaan yang sedang aktif
    public function pemeliharaanAktif()
    {
        return $this->hasOne(Pemeliharaan::class)
                    ->whereNotIn('status', ['Selesai']);
    }
}