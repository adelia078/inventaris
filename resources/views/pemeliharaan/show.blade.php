@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pemeliharaan Barang</h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Kode Barang</th>
                                    <td>{{ $pemeliharaan->barang->kode_barang ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Barang</th>
                                    <td>{{ $pemeliharaan->barang->nama_barang ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Rusak</th>
                                    <td>{{ $pemeliharaan->tanggal_rusak->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Penanggung Jawab</th>
                                    <td>{{ $pemeliharaan->penanggung_jawab }}</td>
                                </tr>
                                <tr>
                                    <th>Biaya Perbaikan</th>
                                    <td>
                                        <strong class="text-primary">
                                            Rp {{ number_format($pemeliharaan->biaya_perbaikan, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-{{ $pemeliharaan->statusBadge }} fs-6">
                                            {{ $pemeliharaan->status }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5 class="text-muted">Deskripsi Kerusakan</h5>
                                <div class="border rounded p-3 bg-light">
                                    {{ $pemeliharaan->kerusakan }}
                                </div>
                            </div>

                            @if($pemeliharaan->keterangan)
                            <div class="mb-3">
                                <h5 class="text-muted">Keterangan Tambahan</h5>
                                <div class="border rounded p-3 bg-light">
                                    {{ $pemeliharaan->keterangan }}
                                </div>
                            </div>
                            @endif

                            <div class="mb-3">
                                <h5 class="text-muted">Informasi Waktu</h5>
                                <small class="d-block">
                                    <strong>Dibuat:</strong> {{ $pemeliharaan->created_at->format('d/m/Y H:i') }}
                                </small>
                                <small class="d-block">
                                    <strong>Terakhir Diupdate:</strong> {{ $pemeliharaan->updated_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('pemeliharaan.edit', $pemeliharaan) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('pemeliharaan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <form action="{{ route('pemeliharaan.destroy', $pemeliharaan) }}" 
                          method="POST" 
                          class="d-inline"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection