<x-main-layout :title-page="'Laporan Peminjaman Barang'">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📊 Laporan Peminjaman Barang</h5>
                <button onclick="window.print()" class="btn btn-light btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                    </svg>
                    Cetak Laporan
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Filter Laporan -->
            <div class="filter-area mb-4">
                <form action="{{ route('peminjaman.laporan') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" class="form-control" 
                               value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Sampai</label>
                        <input type="date" name="tanggal_sampai" class="form-control" 
                               value="{{ request('tanggal_sampai') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Nama Peminjam</label>
                        <input type="text" name="peminjam" class="form-control" 
                               placeholder="Cari peminjam..." value="{{ request('peminjam') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
                <hr>
            </div>

            <!-- Statistik -->
            <div class="stats-area row mb-4">
                <div class="col-md-3 col-6 mb-2">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Total Peminjaman</h6>
                            <h3 class="mb-0"><strong>{{ $total }}</strong></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card bg-warning bg-opacity-25">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Sedang Dipinjam</h6>
                            <h3 class="mb-0"><strong>{{ $dipinjam }}</strong></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card bg-danger bg-opacity-25">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Terlambat</h6>
                            <h3 class="mb-0"><strong>{{ $terlambat }}</strong></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card bg-success bg-opacity-25">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Sudah Dikembalikan</h6>
                            <h3 class="mb-0"><strong>{{ $dikembalikan }}</strong></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Area yang Akan Dicetak -->
            <div id="printArea">
                <div class="text-center mb-4">
                    <h4 class="mb-1"><strong>Laporan Peminjaman Barang</strong></h4>
                    <p class="mb-0">Tanggal Cetak: {{ now()->format('d F Y') }}</p>
                </div>

                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center">No</th>
                            <th width="9%">Kode</th>
                            <th width="18%">Nama Barang</th>
                            <th width="13%">Peminjam</th>
                            <th width="11%">Kontak</th>
                            <th width="7%" class="text-center">Jumlah</th>
                            <th width="9%">Tgl Pinjam</th>
                            <th width="9%">Tgl Kembali</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="10%">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $index => $p)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $p->kode_peminjaman }}</td>
                            <td>
                                {{ $p->barang->nama_barang ?? $p->barang->nama ?? '-' }}<br>
                                <small>{{ $p->barang->kode_barang }}</small>
                            </td>
                            <td>{{ $p->nama_peminjam }}</td>
                            <td>{{ $p->telepon_peminjam ?? $p->email_peminjam ?? '-' }}</td>
                            <td class="text-center">{{ $p->jumlah_dipinjam }}</td>
                            <td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                            <td>{{ $p->tanggal_kembali_rencana->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $p->status }}</td>
                            <td>
                                Pinjam: {{ $p->kondisi_pinjam }}
                                @if($p->kondisi_kembali)
                                    <br>Kembali: {{ $p->kondisi_kembali }}
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-3">
                                Tidak ada data peminjaman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tombol Kembali -->
            <div class="mt-3">
                <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                    </svg>
                    Kembali ke Daftar Peminjaman
                </a>
            </div>
        </div>
    </div>

    <style>
        @media print {
            /* Sembunyikan yang tidak perlu */
            .card-header,
            .filter-area,
            .stats-area,
            .btn,
            nav,
            .navbar,
            .sidebar,
            aside,
            footer {
                display: none !important;
            }
            
            /* Setup halaman */
            @page {
                size: A4 landscape;
                margin: 1.5cm 1cm;
            }
            
            body {
                margin: 0;
                padding: 20px;
                font-family: Arial, sans-serif;
                font-size: 11px;
            }
            
            /* Print area */
            #printArea {
                display: block !important;
            }
            
            /* Header */
            #printArea h4 {
                font-size: 16px;
                font-weight: bold;
                margin: 0 0 5px 0;
                color: #000;
            }
            
            #printArea p {
                font-size: 11px;
                margin: 0 0 20px 0;
                color: #333;
            }
            
            /* Table */
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10px;
                margin-top: 10px;
            }
            
            th, td {
                border: 1px solid #000 !important;
                padding: 6px 8px;
                text-align: left;
                vertical-align: top;
            }
            
            thead th {
                background-color: #f0f0f0 !important;
                font-weight: bold;
                text-align: center;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            tbody td {
                color: #000 !important;
            }
            
            .text-center {
                text-align: center !important;
            }
            
            small {
                font-size: 9px;
                color: #666 !important;
            }
            
            tr {
                page-break-inside: avoid;
            }
            
            thead {
                display: table-header-group;
            }
            
            tbody {
                display: table-row-group;
            }
        }
    </style>
</x-main-layout>