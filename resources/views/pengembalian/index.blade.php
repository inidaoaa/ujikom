@extends('layouts.index')

@section('content')
@include('sweetalert::alert')
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

<div class="container mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">Data Pengembalian</h5>
                <!-- Tombol Tambah Data untuk Membuka Modal -->
                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            </div>

            <div class="card-body">
                <!-- Form Pencarian -->
                <form action="{{ route('pengembalian.index') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama atau jenis barang..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
                <!-- End Form Pencarian -->

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-white">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Nama Barang</th>
                                <th>Jenis Barang</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Lokasi Awal</th>
                                <th>Lokasi Pinjam</th>
                                <th>Ruangan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse ($pengembalian as $data)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $data->nama_peminjam }}</td>
                                <td>{{ $data->dataBarang ? $data->dataBarang->nama_barang : 'Barang Tidak Ditemukan' }}</td>
                                <td>{{ $data->jenis_barang }}</td>
                                <td>{{ $data->tanggal_pinjam }}</td>
                                <td>{{ $data->tanggal_kembali }}</td>
                                <td>{{ $data->lokasi_awal }}</td>
                                <td>{{ $data->lokasi_pinjam }}</td>
                                <td>{{ $data->ruangan }}</td>
                                <td class="text-center">{{ $data->jumlah }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ ucfirst($data->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">
                                    <em>Data belum tersedia.</em>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $pengembalian->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createModalLabel">Form Pengembalian Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pengembalian.store') }}" method="POST">
                    @csrf

                    <!-- Pilih Peminjaman -->
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

                    <!-- Tanggal Kembali -->
                    <div class="mb-3">
                        <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control @error('tanggal_kembali') is-invalid @enderror" required>
                        @error('tanggal_kembali')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="Dikembalikan" selected>Dikembalikan</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
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
@endsection
