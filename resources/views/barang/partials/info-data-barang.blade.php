<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th style="width: 30%;">Kode Barang</th>
            <td>
                <strong>{{ $barang->kode_barang }}</strong>
                @if($barang->jenis_barang === 'Unik')
                    <span class="badge bg-secondary ms-2">Barang Unik</span>
                @else
                    <span class="badge bg-info ms-2">Barang Massal</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Nama Barang</th>
            <td>{{ $barang->nama_barang }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $barang->kategori->nama_kategori }}</td>
        </tr>
        <tr>
            <th>Lokasi</th>
            <td>{{ $barang->lokasi->nama_lokasi }}</td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>{{ $barang->jumlah }} {{ $barang->satuan }}</td>
        </tr>
        <tr>
            <th>Kondisi</th>
            <td>
                @php 
                  $badgeClass = 'bg-success';
                  if ($barang->kondisi == 'Rusak Ringan') {
                    $badgeClass = 'bg-warning text-dark';
                  }
                  if ($barang->kondisi == 'Rusak Berat') {
                    $badgeClass = 'bg-danger';
                  }
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $barang->kondisi }}</span>
            </td>
        </tr>
        <tr>
            <th>Sumber Dana</th>
            <td>
                @php
                    $badgeSumber = 'bg-primary';
                    if ($barang->sumber_dana == 'Swadaya') {
                        $badgeSumber = 'bg-success';
                    }
                    if ($barang->sumber_dana == 'Donatur') {
                        $badgeSumber = 'bg-info';
                    }
                @endphp
                <span class="badge {{ $badgeSumber }}">
                    <i class="bi bi-wallet2"></i> {{ $barang->sumber_dana }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Status Peminjaman</th>
            <td>
                @if($barang->boleh_dipinjam)
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Boleh Dipinjam
                    </span>
                @else
                    <span class="badge bg-danger">
                        <i class="bi bi-x-circle"></i> Tidak Boleh Dipinjam
                    </span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Tanggal Pengadaan</th>
            <td>{{ \Carbon\Carbon::parse($barang->tanggal_pengadaan)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <th>Terakhir Diperbarui</th>
            <td>{{ $barang->updated_at->translatedFormat('d F Y, H:i') }}</td>
        </tr>
    </tbody>
</table>