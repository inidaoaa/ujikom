@extends('layouts.index')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Data Barang Musnah</h5>
                    <a href="{{ route('barangmusnah.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('barangmusnah.store') }}" method="POST">
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

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pemusnahan</label>
                            <input type="date" class="form-control @error('tanggal_pemusnahan') is-invalid @enderror"
                                name="tanggal_pemusnahan" value="{{ old('tanggal_pemusnahan') }}" required>
                            @error('tanggal_pemusnahan')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                name="jumlah" value="{{ old('jumlah') }}" placeholder="Jumlah Barang Musnah" required>
                            @error('jumlah')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                name="keterangan" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: '{!! implode("<br>", $errors->all()) !!}',
        });
    </script>
@endif
