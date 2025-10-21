<x-main-layout :title-page="'Detail Peminjaman'">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Informasi Peminjaman</h5>
            <span class="badge {{ $peminjaman->status == 'Dipinjam' ? 'bg-warning text-dark' : ($peminjaman->status == 'Terlambat' ? 'bg-danger' : 'bg-success') }} fs-6">
                {{ $peminjaman->status }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Data Peminjaman</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Kode Peminjaman</th>
                            <td>: <strong>{{ $peminjaman->kode_peminjaman }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <td>: {{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kembali (Rencana)</th>
                            <td>: {{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</td>
                        </tr>
                        @if($peminjaman->tanggal_kembali_aktual)
                        <tr>
                            <th>Tanggal Kembali (Aktual)</th>
                            <td>: <span class="text-success">{{ $peminjaman->tanggal_kembali_aktual->format('d F Y') }}</span></td>
                        </tr>
                        @endif
                        <tr>
                            <th>Durasi Peminjaman</th>
                            <td>: {{ $peminjaman->durasi }} hari</td>
                        </tr>
                    </table>

                    <h5 class="mt-4">Data Peminjam</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Nama</th>
                            <td>: {{ $peminjaman->nama_peminjam }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>: {{ $peminjaman->email_peminjam ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>No. Telepon</th>
                            <td>: {{ $peminjaman->telepon_peminjam ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5>Data Barang</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Kode Barang</th>
                            <td>: {{ $peminjaman->barang->kode_barang }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>: <strong>{{ $peminjaman->barang->nama_barang ?? $peminjaman->barang->nama ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>: {{ $peminjaman->barang->kategori->nama_kategori ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>: {{ $peminjaman->barang->lokasi->nama_lokasi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Dipinjam</th>
                            <td>: <strong>{{ $peminjaman->jumlah_dipinjam }} Unit</strong></td>
                        </tr>
                    </table>

                    <h5 class="mt-4">Kondisi Barang</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Kondisi Saat Pinjam</th>
                            <td>: <span class="badge bg-info">{{ $peminjaman->kondisi_pinjam }}</span></td>
                        </tr>
                        @if($peminjaman->kondisi_kembali)
                        <tr>
                            <th>Kondisi Saat Kembali</th>
                            <td>: 
                                <span class="badge {{ $peminjaman->kondisi_kembali == 'Baik' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $peminjaman->kondisi_kembali }}
                                </span>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($peminjaman->keterangan)
            <hr>
            <h5>Keterangan</h5>
            <p class="text-muted" style="white-space: pre-line;">{{ $peminjaman->keterangan }}</p>
            @endif

            <hr>
            <div class="d-flex gap-2">
                @if($peminjaman->status != 'Dikembalikan')
                <a href="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" class="btn btn-success">
                    <i class="fas fa-undo"></i> Kembalikan Barang
                </a>
                @endif
                <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</x-main-layout>