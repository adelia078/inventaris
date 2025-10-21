@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Data Pemeliharaan Barang</h3>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pemeliharaan.laporan') }}" 
                        class="btn btn-success" 
                        target="_blank">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </a>
                        <a href="{{ route('pemeliharaan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Pemeliharaan
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Barang</th>
                                    <th>Tanggal Rusak</th>
                                    <th>Kerusakan</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Biaya Perbaikan</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                           <!-- Ganti bagian tbody di pemeliharaan/index.blade.php -->

                        <tbody>
                            @forelse($pemeliharaan as $item)
                            <tr>
                                <td>{{ $pemeliharaan->firstItem() + $loop->index }}</td>
                               <td>
                                    @if($item->barang)
                                        <strong>{{ $item->barang->nama_barang }}</strong><br>
                                        <small class="text-muted">{{ $item->barang->kode_barang }}</small>
                                    @else
                                        <span class="text-danger">
                                            <strong>Barang Tidak Ditemukan</strong><br>
                                            <small>ID: {{ $item->barang_id ?? 'NULL' }}</small>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $item->tanggal_rusak->format('d/m/Y') }}</td>
                                <td>{{ Str::limit($item->kerusakan, 50) }}</td>
                                <td>{{ $item->penanggung_jawab }}</td>
                                <td>Rp {{ number_format($item->biaya_perbaikan, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->statusBadge }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('pemeliharaan.show', $item) }}" 
                                        class="btn btn-info btn-sm"
                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; padding: 0; border-radius: 8px;"
                                        title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('pemeliharaan.edit', $item) }}" 
                                        class="btn btn-warning btn-sm"
                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; padding: 0; border-radius: 8px;"
                                        title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                            </svg>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm"
                                                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; padding: 0; border-radius: 8px;"
                                                title="Hapus"
                                                onclick="confirmDelete({{ $item->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $item->id }}" 
                                        action="{{ route('pemeliharaan.destroy', $item) }}" 
                                        method="POST" 
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data pemeliharaan</td>
                            </tr>
                            @endforelse
                        </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $pemeliharaan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus data ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection