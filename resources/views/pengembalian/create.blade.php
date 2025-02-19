@extends('layouts.index')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Pengembalian Barang</h5>
                    <a href="{{ route('pengembalian.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengembalian.store') }}" method="POST">
                        @csrf

                        {{-- Pilih Peminjaman --}}
                        <div class="mb-3">
                            <label for="id_peminjaman" class="form-label">Pilih Peminjaman</label>
                            <select name="id_peminjaman" id="id_peminjaman" class="form-control @error('id_peminjaman') is-invalid @enderror">
                                <option value="" disabled selected>Pilih Peminjaman</option>
                                @foreach($peminjaman as $pinjam)
                                    <option value="{{ $pinjam->id }}">
                                        {{ $pinjam->nama_peminjam }} - {{ $pinjam->jenis_barang }} (Dipinjam: {{ $pinjam->tanggal_pinjam }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_peminjaman')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Tanggal Kembali --}}
                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control @error('tanggal_kembali') is-invalid @enderror" required>
                            @error('tanggal_kembali')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="Dikembalikan" selected>Dikembalikan</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
