<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class BarangController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:manage barang', except: ['destroy']),
            new Middleware('permission:delete barang', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $barangs = Barang::with(['kategori', 'lokasi'])
        ->when($search, function ($query, $search) {
            $query->where('nama_barang', 'like', '%' . $search . '%')
            ->orWhere('kode_barang', 'like', '%' . $search . '%');
        })
        ->latest()->paginate()->withQueryString();

        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $lokasi = Lokasi::all();

        $barang = new Barang();

        return view('barang.create', compact('barang', 'kategori', 'lokasi'));
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:50',
            'kategori_id' => 'required|exists:kategoris,id',
            'lokasi_id'   => 'required|exists:lokasis,id',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tanggal_pengadaan' => 'required|date',
            'sumber_dana' => 'required|in:Pemerintah,Swadaya,Donatur',
            'jenis_barang' => 'required|in:Unik,Massal',
            'boleh_dipinjam' => 'nullable|boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Set default boleh_dipinjam jika tidak dicentang
        $validated['boleh_dipinjam'] = $request->has('boleh_dipinjam') ? 1 : 0;

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store(null, 'gambar-barang');
        }

        DB::beginTransaction();
        try {
            // Jika jenis barang UNIK dan jumlah > 1, buat multiple records
            if ($validated['jenis_barang'] === 'Unik' && $validated['jumlah'] > 1) {
                $kodeBase = $validated['kode_barang'];
                $jumlahTotal = $validated['jumlah'];

                for ($i = 1; $i <= $jumlahTotal; $i++) {
                    $dataBarang = $validated;
                    $dataBarang['kode_barang'] = $kodeBase . str_pad($i, 3, '0', STR_PAD_LEFT);
                    $dataBarang['jumlah'] = 1; // Setiap record hanya 1 unit
                    
                    Barang::create($dataBarang);
                }
            } else {
                // Jika MASSAL atau jumlah = 1, buat single record
                Barang::create($validated);
            }

            DB::commit();

            return redirect()->route('barang.index')
                ->with('success', 'Data barang berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Gagal menambahkan data barang: ' . $e->getMessage());
        }
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'lokasi']);

        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();
        $lokasi = Lokasi::all();

        return view('barang.edit', compact('barang', 'kategori', 'lokasi'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:50',
            'kategori_id' => 'required|exists:kategoris,id',
            'lokasi_id'   => 'required|exists:lokasis,id',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tanggal_pengadaan' => 'required|date',
            'sumber_dana' => 'required|in:Pemerintah,Swadaya,Donatur',
            'jenis_barang' => 'required|in:Unik,Massal',
            'boleh_dipinjam' => 'nullable|boolean',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Set boleh_dipinjam
        $validated['boleh_dipinjam'] = $request->has('boleh_dipinjam') ? 1 : 0;

        if($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('gambar-barang')->delete($barang->gambar);
            }

            $validated['gambar'] = $request->file('gambar')->store(null, 'gambar-barang');
        }

        $barang->update($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->gambar) {
            Storage::disk('gambar-barang')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil dihapus.');
    }

    public function cetakLaporan()
    {
        $barangs = Barang::with(['kategori', 'lokasi'])->get();

        $data = [
            'title' => 'Laporan Data Barang Inventaris',
            'date' => date('d F Y'),
            'barangs' => $barangs
        ];

        $pdf = Pdf::loadView('barang.laporan', $data);

        return $pdf->stream('laporan-inventaris-barang.pdf');
    }
}