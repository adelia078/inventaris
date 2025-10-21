<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\User;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        // Data yang sudah ada
        $jumlahBarang = Barang::count();
        $jumlahKategori = Kategori::count();
        $jumlahLokasi = Lokasi::count();
        $jumlahUser = User::count();

        // Data Peminjaman
        $jumlahPeminjamanAktif = Peminjaman::whereIn('status', ['Dipinjam', 'Terlambat'])->count();
        $jumlahTerlambat = Peminjaman::where('status', 'Terlambat')->count();

        // Data Kondisi Barang
        $kondisiBaik = Barang::where('kondisi', 'Baik')->count();
        $kondisiRusakRingan = Barang::where('kondisi', 'Rusak Ringan')->count();
        $kondisiRusakBerat = Barang::where('kondisi', 'Rusak Berat')->count();

        // Barang Terbaru
        $barangTerbaru = Barang::with(['kategori', 'lokasi'])->latest()->take(5)->get();

        // Peminjaman Terbaru
        $peminjamanTerbaru = Peminjaman::with(['barang'])
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->latest()
            ->take(5)
            ->get();


        return view('dashboard', compact(
            'jumlahBarang',
            'jumlahKategori',
            'jumlahLokasi',
            'jumlahUser',
            'jumlahPeminjamanAktif',
            'jumlahTerlambat',
            'kondisiBaik',
            'kondisiRusakRingan',
            'kondisiRusakBerat',
            'barangTerbaru',
            'peminjamanTerbaru',
        ));
    }
}