@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Data Pemeliharaan</h3>
                </div>
                
                <form action="{{ route('pemeliharaan.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="barang_id" class="form-label">
                                        Barang <span class="text-danger">*</span>
                                    </label>
                                    <select name="barang_id" id="barang_id" 
                                            class="form-select @error('barang_id') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($barangs as $barang)
                                        <option value="{{ $barang->id }}" 
                                                {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                            {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('barang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tanggal_rusak" class="form-label">
                                        Tanggal Rusak <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           name="tanggal_rusak" 
                                           id="tanggal_rusak" 
                                           class="form-control @error('tanggal_rusak') is-invalid @enderror" 
                                           value="{{ old('tanggal_rusak', date('Y-m-d')) }}" 
                                           required>
                                    @error('tanggal_rusak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="penanggung_jawab" class="form-label">
                                        Penanggung Jawab <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="penanggung_jawab" 
                                           id="penanggung_jawab" 
                                           class="form-control @error('penanggung_jawab') is-invalid @enderror" 
                                           value="{{ old('penanggung_jawab') }}" 
                                           placeholder="Nama Teknisi/Petugas"
                                           required>
                                    @error('penanggung_jawab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="biaya_perbaikan" class="form-label">
                                        Biaya Perbaikan (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           name="biaya_perbaikan" 
                                           id="biaya_perbaikan" 
                                           class="form-control @error('biaya_perbaikan') is-invalid @enderror" 
                                           value="{{ old('biaya_perbaikan', 0) }}" 
                                           min="0"
                                           step="0.01"
                                           placeholder="0"
                                           required>
                                    @error('biaya_perbaikan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" id="status" 
                                            class="form-select @error('status') is-invalid @enderror" 
                                            required>
                                        <option value="Rusak" {{ old('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                        <option value="Dalam Perbaikan" {{ old('status') == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                        <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="Tidak Bisa Diperbaiki" {{ old('status') == 'Tidak Bisa Diperbaiki' ? 'selected' : '' }}>Tidak Bisa Diperbaiki</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="kerusakan" class="form-label">
                                        Deskripsi Kerusakan <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="kerusakan" 
                                              id="kerusakan" 
                                              rows="5" 
                                              class="form-control @error('kerusakan') is-invalid @enderror" 
                                              placeholder="Jelaskan kerusakan barang..." required>{{ old('kerusakan') }}</textarea>
                                    @error('kerusakan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                                    <textarea name="keterangan" 
                                              id="keterangan" 
                                              rows="3" 
                                              class="form-control @error('keterangan') is-invalid @enderror" 
                                              placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('pemeliharaan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
