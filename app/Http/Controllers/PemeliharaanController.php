<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use App\Models\Barang;
use Illuminate\Http\Request;

class PemeliharaanController extends Controller
{
    /**
     * Tampilkan daftar semua data pemeliharaan
     */
    public function index()
    {
        // Pakai pagination agar fungsi firstItem() & links() di blade bisa jalan
        $pemeliharaan = Pemeliharaan::with('barang')->latest()->paginate(10);

        return view('pemeliharaan.index', compact('pemeliharaan'));
    }

    /**
     * Form untuk menambahkan data baru
     */
    public function create()
    {
        $barangs = Barang::all();
        return view('pemeliharaan.create', compact('barangs'));
    }

    /**
     * Simpan data baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_rusak' => 'required|date',
            'kerusakan' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'biaya_perbaikan' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        Pemeliharaan::create($request->all());

        return redirect()->route('pemeliharaan.index')->with('success', 'Data pemeliharaan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit data
     */
    public function edit(Pemeliharaan $pemeliharaan)
    {
        $barangs = Barang::all();
        return view('pemeliharaan.edit', compact('pemeliharaan', 'barangs'));
    }

    /**
     * Update data ke database
     */
    public function update(Request $request, Pemeliharaan $pemeliharaan)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_rusak' => 'required|date',
            'kerusakan' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'biaya_perbaikan' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $pemeliharaan->update($request->all());

        return redirect()->route('pemeliharaan.index')->with('success', 'Data pemeliharaan berhasil diperbarui.');
    }

    /**
     * Hapus data pemeliharaan
     */
    public function destroy(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->delete();
        return redirect()->route('pemeliharaan.index')->with('success', 'Data pemeliharaan berhasil dihapus.');
    }

    /**
     * Tampilkan detail data pemeliharaan
     */
    public function show(Pemeliharaan $pemeliharaan)
    {
        return view('pemeliharaan.show', compact('pemeliharaan'));
    }

    // Tambahkan method ini di PemeliharaanController.php

public function cetakLaporan(Request $request)
{
    $query = Pemeliharaan::with('barang');
    
    // Filter berdasarkan status jika ada
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    // Filter berdasarkan tanggal jika ada
    if ($request->filled('tanggal_dari')) {
        $query->whereDate('tanggal_rusak', '>=', $request->tanggal_dari);
    }
    
    if ($request->filled('tanggal_sampai')) {
        $query->whereDate('tanggal_rusak', '<=', $request->tanggal_sampai);
    }
    
    $pemeliharaan = $query->orderBy('tanggal_rusak', 'desc')->get();
    
    // Hitung total biaya
    $totalBiaya = $pemeliharaan->sum('biaya_perbaikan');
    
    // Hitung statistik per status
    $statistik = [
        'total' => $pemeliharaan->count(),
        'rusak' => $pemeliharaan->where('status', 'Rusak')->count(),
        'dalam_perbaikan' => $pemeliharaan->where('status', 'Dalam Perbaikan')->count(),
        'selesai' => $pemeliharaan->where('status', 'Selesai')->count(),
        'tidak_bisa_diperbaiki' => $pemeliharaan->where('status', 'Tidak Bisa Diperbaiki')->count(),
    ];
    
    return view('pemeliharaan.laporan', compact('pemeliharaan', 'totalBiaya', 'statistik'));
}

}
