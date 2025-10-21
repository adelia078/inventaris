<x-table-list>
    <x-slot name="header">
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Jumlah</th>
            <th>Kondisi</th>
            <th>Status Pinjam</th>
            <th>&nbsp;</th>
        </tr>
    </x-slot>

    @forelse ($barangs as $index => $barang)
        @php
            // Hitung jumlah yang sedang dipinjam
            $jumlahDipinjam = $barang->peminjaman()
                ->whereIn('status', ['Dipinjam', 'Terlambat'])
                ->sum('jumlah_dipinjam');
        @endphp
        <tr>
            <td>{{ $barangs->firstItem() + $index }}</td>
            <td>
                <div>
                    <strong>{{ $barang->kode_barang }}</strong>
                    <br>
                    @if($barang->jenis_barang === 'Unik')
                        <small class="badge bg-secondary">Unik</small>
                    @else
                        <small class="badge bg-light text-dark">Massal</small>
                    @endif
                </div>
            </td>
            <td>{{ $barang->nama_barang }}</td>
            <td>{{ $barang->kategori->nama_kategori }}</td>
            <td>{{ $barang->lokasi->nama_lokasi }}</td>
            <td>
                <div>
                    <strong>{{ $barang->jumlah }} {{ $barang->satuan }}</strong>
                    @if($jumlahDipinjam > 0)
                        <br>
                        <small class="text-warning">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $jumlahDipinjam }} {{ $barang->satuan }} sedang dipinjam
                        </small>
                    @endif
                    <br>
                    <small class="text-muted">
                        <i class="bi bi-wallet2"></i> {{ $barang->sumber_dana }}
                    </small>
                </div>
            </td>
            <td>
                <span class="badge bg-info">{{ $barang->kondisi }}</span>
            </td>
            <td>
                @if($barang->boleh_dipinjam)
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Boleh
                    </span>
                @else
                    <span class="badge bg-danger">
                        <i class="bi bi-x-circle"></i> Tidak
                    </span>
                @endif
            </td>
            <td class="text-end">
                @can('manage barang')
                    <x-tombol-aksi href="{{ route('barang.show', $barang->id) }}" type="show" />
                    <x-tombol-aksi href="{{ route('barang.edit', $barang->id) }}" type="edit" />
                @endcan

                @can('delete barang')
                    <x-tombol-aksi :href="route('barang.destroy', $barang->id)" type="delete" />
                @endcan
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center">
                <div class="alert alert-danger">
                    Data barang belum tersedia.
                </div>
            </td>
        </tr>
    @endforelse
</x-table-list>