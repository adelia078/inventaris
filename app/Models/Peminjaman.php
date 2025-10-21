<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'barang_id',
        'nama_peminjam',
        'email_peminjam',
        'telepon_peminjam',
        'jumlah_dipinjam',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'kondisi_pinjam',
        'kondisi_kembali',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
    ];

    // Relationship
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Auto generate kode peminjaman
    public static function generateKode()
    {
        $lastPeminjaman = self::orderBy('id', 'desc')->first();
        
        if (!$lastPeminjaman) {
            return 'PJM001';
        }
        
        $lastNumber = intval(substr($lastPeminjaman->kode_peminjaman, 3));
        $newNumber = $lastNumber + 1;
        
        return 'PJM' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    // Check apakah terlambat
    public function isTerlambat()
    {
        if ($this->status == 'Dikembalikan') {
            return false;
        }
        
        return Carbon::now()->greaterThan($this->tanggal_kembali_rencana);
    }

    // Hitung durasi peminjaman
    public function getDurasiAttribute()
    {
        if ($this->tanggal_kembali_aktual) {
            return $this->tanggal_pinjam->diffInDays($this->tanggal_kembali_aktual);
        }
        
        return $this->tanggal_pinjam->diffInDays(Carbon::now());
    }
}