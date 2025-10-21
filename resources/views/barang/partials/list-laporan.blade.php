<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Jumlah</th>
            <th>Kondisi</th>
            <th>Sumber Dana</th>
            <th>Status Pinjam</th>
            <th>Tgl. Pengadaan</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($barangs as $index => $barang)

        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $barang->kode_barang }}</td>
            <td>{{ $barang->nama_barang }}</td>
            <td>{{ $barang->kategori->nama_kategori }}</td>
            <td>{{ $barang->lokasi->nama_lokasi }}</td>
            <td>{{ $barang->jumlah }} {{ $barang->satuan }}</td>
            <td>{{ $barang->kondisi }}</td>
            <td>{{ $barang->sumber_dana }}</td>
            <td>{{ $barang->boleh_dipinjam ? 'Boleh' : 'Tidak' }}</td>
            <td>
                {{ date('d-m-Y', strtotime($barang->tanggal_pengadaan)) }}
            </td>
        </tr>

    @empty
    <tr>
        <td colspan="10" style="text-align: center;">Tidak ada data.</td>
    </tr>
    @endforelse
    </tbody>
</table>