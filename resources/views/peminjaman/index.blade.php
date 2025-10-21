<x-main-layout :title-page="'Peminjaman'">
    <div class="card">
        <div class="card-body">
            <!-- Header dengan Button dan Search -->
            <div class="row mb-3 align-items-center">
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <!-- Tombol Tambah & Cetak -->
                        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                            Tambah Peminjaman
                        </a>
                        <a href="{{ route('peminjaman.laporan') }}" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                            </svg>
                            Cetak Laporan Peminjaman
                        </a>

                        <!-- Search & Filter di sebelah kanan -->
                        <form action="{{ route('peminjaman.index') }}" method="GET" class="d-flex gap-2 ms-auto">
                            <div class="input-group" style="width: 300px;">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Cari kode/nama peminjam/barang..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                    </svg>
                                </button>
                            </div>
                            <select name="status" class="form-select" style="width: 180px;" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
    
            <x-notif-alert class="mt-3"/>

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="3%" class="text-center">#</th>
                            <th width="8%">Kode</th>
                            <th width="18%">Nama Barang</th>
                            <th width="12%">Peminjam</th>
                            <th width="10%">No. Telepon</th>
                            <th width="6%" class="text-center">Jumlah</th>
                            <th width="8%">Tgl Pinjam</th>
                            <th width="8%">Tgl Kembali</th>
                            <th width="8%" class="text-center">Status</th>
                            <th width="19%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $index => $p)
                        <tr>
                            <td class="text-center">{{ $peminjaman->firstItem() + $index }}</td>
                            <td><strong>{{ $p->kode_peminjaman }}</strong></td>
                            <td>
                                <strong>{{ $p->barang->nama_barang ?? $p->barang->nama ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $p->barang->kode_barang }}</small>
                            </td>
                            <td>{{ $p->nama_peminjam }}</td>
                            <td>{{ $p->telepon_peminjam ?? '-' }}</td>
                            <td class="text-center">{{ $p->jumlah_dipinjam }} Unit</td>
                            <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</strong>
                                @if($p->tanggal_kembali_aktual)
                                    <br><small class="text-success">
                                        ✓ {{ $p->tanggal_kembali_aktual->format('d/m/Y') }}
                                    </small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->status == 'Dipinjam')
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                @elseif($p->status == 'Terlambat')
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-success">Dikembalikan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('peminjaman.show', $p->id) }}" 
                                       class="btn btn-info btn-sm text-white" title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 3px;">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                        Detail
                                    </a>
                                    
                                    @if($p->status != 'Dikembalikan')
                                    <a href="{{ route('peminjaman.kembalikan', $p->id) }}" 
                                       class="btn btn-success btn-sm" title="Pengembalian">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 3px;">
                                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                            <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                        </svg>
                                        Kembalikan
                                    </a>
                                    @endif

                                    @if($p->status == 'Dikembalikan')
                                    <form action="{{ route('peminjaman.destroy', $p->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Hapus data peminjaman ini?')" 
                                                title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 3px;">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="display: block; margin: 0 auto 1rem;">
                                    <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                    <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2.002 3h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
                                </svg>
                                <p>Tidak ada data peminjaman</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-body">
            {{ $peminjaman->links() }}
        </div>
    </div>
</x-main-layout>