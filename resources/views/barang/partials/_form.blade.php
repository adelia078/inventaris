@csrf
<div class="row mb-3">
    <div class="col-md-6">
        <x-form-input label="Kode Barang" name="kode_barang" :value="$barang->kode_barang" />
        <small class="text-muted">
            <i class="bi bi-info-circle"></i> 
            Untuk barang unik, kode akan di-generate otomatis sesuai jumlah
        </small>
    </div>

    <div class="col-md-6">
        <x-form-input label="Nama Barang" name="nama_barang" :value="$barang->nama_barang" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <x-form-select label="Kategori" name="kategori_id" :value="$barang->kategori_id"
            :option-data="$kategori" option-label="nama_kategori" option-value="id" />
    </div>

    <div class="col-md-6">
        <x-form-select label="Lokasi" name="lokasi_id" :value="$barang->lokasi_id"
            :option-data="$lokasi" option-label="nama_lokasi" option-value="id" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <x-form-input label="Jumlah" name="jumlah" :value="$barang->jumlah" type="number" />
    </div>

    <div class="col-md-6">
        <x-form-input label="Satuan" name="satuan" :value="$barang->satuan" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        @php
            $kondisi = [
               [ 'kondisi' => 'Baik'],
               [ 'kondisi' => 'Rusak Ringan'],
               [ 'kondisi' => 'Rusak Berat']
            ];
        @endphp

        <x-form-select label="Kondisi" name="kondisi" :value="$barang->kondisi" :option-data="$kondisi"
            option-label="kondisi" option-value="kondisi" />
    </div>

    <div class="col-md-6">
        @php
            $tanggal = $barang->tanggal_pengadaan
                ? date('Y-m-d', strtotime($barang->tanggal_pengadaan))
                : null;
        @endphp

        <x-form-input label="Tanggal Pengadaan" name="tanggal_pengadaan" type="date" :value="$tanggal" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        @php
            $sumberDana = [
               [ 'sumber' => 'Pemerintah'],
               [ 'sumber' => 'Swadaya'],
               [ 'sumber' => 'Donatur']
            ];
        @endphp

        <x-form-select label="Sumber Dana" name="sumber_dana" :value="$barang->sumber_dana" 
            :option-data="$sumberDana" option-label="sumber" option-value="sumber" />
    </div>

    <div class="col-md-6">
        @php
            $jenisBarang = [
               [ 'jenis' => 'Massal', 'keterangan' => 'Massal - Kode barang sama untuk semua unit'],
               [ 'jenis' => 'Unik', 'keterangan' => 'Unik - Generate kode otomatis per unit']
            ];
        @endphp

        <x-form-select label="Jenis Barang" name="jenis_barang" :value="$barang->jenis_barang" 
            :option-data="$jenisBarang" option-label="keterangan" option-value="jenis" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Status Peminjaman</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="boleh_dipinjam" 
                   id="boleh_dipinjam" value="1" 
                   {{ old('boleh_dipinjam', $barang->boleh_dipinjam ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="boleh_dipinjam">
                Boleh Dipinjam
            </label>
        </div>
        <small class="text-muted">Centang jika barang ini dapat dipinjam</small>
    </div>

    <div class="col-md-6">
        <x-form-input label="Gambar Barang" name="gambar" type="file" />
    </div>
</div>

<div class="mt-4">
    <x-primary-button>
        {{ isset($update) ? __('Update') : __('Simpan') }}
    </x-primary-button>

    <x-tombol-kembali :href="route('barang.index')" />
</div>