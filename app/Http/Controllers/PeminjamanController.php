<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['barang.kategori', 'barang.lokasi']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhere('nama_peminjam', 'like', "%{$search}%")
                  ->orWhereHas('barang', function($q) use ($search) {
                      $q->where('nama_barang', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Update status terlambat
        $this->updateStatusTerlambat();

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $barangs = Barang::with(['kategori', 'lokasi'])
            ->where('jumlah', '>', 0)
            ->get();
        
        return view('peminjaman.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'nama_peminjam' => 'required|string|max:100',
            'email_peminjam' => 'nullable|email|max:100',
            'telepon_peminjam' => 'nullable|string|max:20',
            'jumlah_dipinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'kondisi_pinjam' => 'required|in:Baik,Rusak Ringan',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($request->barang_id);

            // Cek stok tersedia (jumlah saat ini di database)
            if ($request->jumlah_dipinjam > $barang->jumlah) {
                return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $barang->jumlah . ' ' . $barang->satuan);
            }

            // Generate kode peminjaman
            $kodePeminjaman = Peminjaman::generateKode();

            // Tentukan status
            $status = 'Dipinjam';
            if (Carbon::parse($request->tanggal_kembali_rencana)->isPast()) {
                $status = 'Terlambat';
            }

            // Buat peminjaman
            Peminjaman::create([
                'kode_peminjaman' => $kodePeminjaman,
                'barang_id' => $request->barang_id,
                'nama_peminjam' => $request->nama_peminjam,
                'email_peminjam' => $request->email_peminjam,
                'telepon_peminjam' => $request->telepon_peminjam,
                'jumlah_dipinjam' => $request->jumlah_dipinjam,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'kondisi_pinjam' => $request->kondisi_pinjam,
                'status' => $status,
                'keterangan' => $request->keterangan
            ]);

            // ✅ KURANGI STOK BARANG
            $barang->decrement('jumlah', $request->jumlah_dipinjam);

            DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['barang.kategori', 'barang.lokasi'])->findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::with(['barang'])->findOrFail($id);
        
        if ($peminjaman->status == 'Dikembalikan') {
            return back()->with('error', 'Barang sudah dikembalikan!');
        }

        return view('peminjaman.kembalikan', compact('peminjaman'));
    }

    public function updateKembali(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi_kembali' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'keterangan_kembali' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status == 'Dikembalikan') {
                return back()->with('error', 'Barang sudah dikembalikan!');
            }

            // Update peminjaman
            $keteranganLengkap = $peminjaman->keterangan;
            if ($request->keterangan_kembali) {
                $keteranganLengkap .= "\n\nKeterangan Pengembalian: " . $request->keterangan_kembali;
            }

            $peminjaman->update([
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'kondisi_kembali' => $request->kondisi_kembali,
                'status' => 'Dikembalikan',
                'keterangan' => $keteranganLengkap
            ]);

            // ✅ KEMBALIKAN STOK BARANG
            $barang = $peminjaman->barang;
            $barang->increment('jumlah', $peminjaman->jumlah_dipinjam);

            // Update kondisi barang jika rusak
            if ($request->kondisi_kembali != 'Baik') {
                $barang->update(['kondisi' => $request->kondisi_kembali]);
            }

            DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Barang berhasil dikembalikan!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            if ($peminjaman->status == 'Dipinjam' || $peminjaman->status == 'Terlambat') {
                return back()->with('error', 'Tidak dapat menghapus peminjaman yang masih aktif!');
            }

            $peminjaman->delete();
            return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 📊 LAPORAN PEMINJAMAN
    public function laporan(Request $request)
    {
        $query = Peminjaman::with(['barang.kategori', 'barang.lokasi']);

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter peminjam
        if ($request->has('peminjam') && $request->peminjam != '') {
            $query->where('nama_peminjam', 'like', '%' . $request->peminjam . '%');
        }

        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get();

        // Statistik
        $total = $peminjaman->count();
        $dipinjam = $peminjaman->where('status', 'Dipinjam')->count();
        $terlambat = $peminjaman->where('status', 'Terlambat')->count();
        $dikembalikan = $peminjaman->where('status', 'Dikembalikan')->count();

        return view('peminjaman.laporan', compact(
            'peminjaman',
            'total',
            'dipinjam',
            'terlambat',
            'dikembalikan'
        ));
    }

    // Helper: Update status terlambat
    private function updateStatusTerlambat()
    {
        Peminjaman::where('status', 'Dipinjam')
            ->where('tanggal_kembali_rencana', '<', Carbon::now())
            ->update(['status' => 'Terlambat']);
    }

    // API: Get barang info
    public function getBarangInfo($id)
    {
        $barang = Barang::with(['kategori', 'lokasi'])->findOrFail($id);
        
        // Hitung jumlah yang sedang dipinjam
        $jumlahDipinjam = Peminjaman::where('barang_id', $id)
            ->whereIn('status', ['Dipinjam', 'Terlambat'])
            ->sum('jumlah_dipinjam');
        
        // Jumlah total = jumlah tersedia sekarang + yang sedang dipinjam
        $jumlahTotal = $barang->jumlah + $jumlahDipinjam;
        
        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $barang->nama_barang,
                'kode' => $barang->kode_barang,
                'kategori' => $barang->kategori->nama_kategori ?? '-',
                'lokasi' => $barang->lokasi->nama_lokasi ?? '-',
                'jumlah_total' => $jumlahTotal,
                'jumlah_dipinjam' => $jumlahDipinjam,
                'jumlah_tersedia' => $barang->jumlah,
                'kondisi' => $barang->kondisi,
                'satuan' => $barang->satuan,
            ]
        ]);
    }
}