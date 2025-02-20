@extends('layouts.index')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Barang Mutasi</h5>
                    <a href="{{ route('barangmutasi.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('barangmutasi.store') }}" method="POST">
                        @csrf


                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <select class="form-control @error('id_databarang') is-invalid @enderror" name="id_databarang" required>
                                <option value="" selected disabled>Pilih Nama Barang</option>
                                @foreach($databarang as $barang)
                                    <option value="{{ $barang->id }}" {{ old('id_databarang') == $barang->id ? 'selected' : '' }}>
                                        {{ $barang->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_databarang')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Barang</label>
                            <select class="form-control @error('jenis_barang') is-invalid @enderror" name="jenis_barang" required>
                                <option value="" disabled selected>Pilih Jenis Barang</option>
                                <option value="Furniture" {{ old('jenis_barang') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="Elektronik" {{ old('jenis_barang') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            </select>
                            @error('jenis_barang')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Tanggal Mutasi -->
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mutasi</label>
                            <input type="date" class="form-control @error('tanggal_mutasi') is-invalid @enderror"
                                   name="tanggal_mutasi" value="{{ old('tanggal_mutasi') }}" required>
                            @error('tanggal_mutasi')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Lokasi Awal -->
                        <div class="mb-3">
                            <label class="form-label">Lokasi Awal</label>
                            <input type="text" class="form-control @error('lokasi_awal') is-invalid @enderror"
                                   name="lokasi_awal" value="{{ old('lokasi_awal') }}" placeholder="Lokasi Awal" required>
                            @error('lokasi_awal')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Lokasi Mutasi -->
                        <div class="mb-3">
                            <label class="form-label">Lokasi Mutasi</label>
                            <input type="text" class="form-control @error('lokasi_mutasi') is-invalid @enderror"
                                   name="lokasi_mutasi" value="{{ old('lokasi_mutasi') }}" placeholder="Lokasi Mutasi" required>
                            @error('lokasi_mutasi')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Ruangan -->
                        <div class="mb-3">
                            <label class="form-label">Ruangan</label>
                            <input type="text" class="form-control @error('ruangan') is-invalid @enderror"
                                   name="ruangan" value="{{ old('ruangan') }}" placeholder="Ruangan" required>
                            @error('ruangan')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Lantai -->
                        <div class="mb-3">
                            <label class="form-label">Lantai</label>
                            <input type="text" class="form-control @error('lantai') is-invalid @enderror"
                                   name="lantai" value="{{ old('lantai') }}" placeholder="Lantai" required>
                            @error('lantai')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                name="keterangan" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
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
@endsection
