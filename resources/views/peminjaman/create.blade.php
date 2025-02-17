@extends('layouts.index')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Data Peminjaman</h5>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Peminjam</label>
                            <input type="text" class="form-control @error('nama_peminjam') is-invalid @enderror" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required>
                            @error('nama_peminjam')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Input untuk barang yang dipinjam -->
                        <div id="barang-container">
                            <div class="barang-item mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Barang</label>
                                        <select class="form-control @error('barang_id.*') is-invalid @enderror" name="barang_id[]" required>
                                            <option value="" selected disabled>Pilih Nama Barang</option>
                                            @foreach($databarang as $barang)
                                                <option value="{{ $barang->id }}" {{ old('barang_id.0') == $barang->id ? 'selected' : '' }}>
                                                    {{ $barang->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('barang_id.*')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" class="form-control @error('jumlah.*') is-invalid @enderror" name="jumlah[]" min="1" required>
                                        @error('jumlah.*')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol untuk menambah barang -->

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pinjam</label>
                            <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}" required>
                            @error('tanggal_pinjam')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control @error('tanggal_kembali') is-invalid @enderror" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}" required>
                            @error('tanggal_kembali')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi Awal</label>
                            <input type="text" class="form-control @error('lokasi_awal') is-invalid @enderror" name="lokasi_awal" value="{{ old('lokasi_awal') }}" required>
                            @error('lokasi_awal')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi Pinjaman</label>
                            <input type="text" class="form-control @error('lokasi_pinjam') is-invalid @enderror" name="lokasi_pinjam" value="{{ old('lokasi_pinjam') }}" required>
                            @error('lokasi_pinjam')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ruangan</label>
                            <input type="text" class="form-control @error('ruangan') is-invalid @enderror" name="ruangan" value="{{ old('ruangan') }}" required>
                            @error('ruangan')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menambah input barang
    function addBarang() {
        const container = document.getElementById('barang-container');
        const newItem = document.createElement('div');
        newItem.classList.add('barang-item', 'mb-3');
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nama Barang</label>
                    <select class="form-control" name="barang_id[]" required>
                        <option value="" selected disabled>Pilih Nama Barang</option>
                        @foreach($databarang as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah</label>
                    <input type="number" class="form-control" name="jumlah[]" min="1" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeBarang(this)">Hapus</button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
    }

    // Fungsi untuk menghapus input barang
    function removeBarang(button) {
        const item = button.closest('.barang-item');
        item.remove();
    }
</script>
@endsection
