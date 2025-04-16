@extends('layouts.index')

@section('content')
@include('sweetalert::alert')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Data Barang</h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>

                <div class="card-body">
                    <!-- Form Pencarian -->
                    <form action="{{ route('databarang.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari Nama Barang..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                    <!-- End Form Pencarian -->

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class=" text-white">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jenis Barang</th>
                                    <th>Merek</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse ($databarang as $data)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $data->nama_barang }}</td>
                                    <td>{{ $data->jenis_barang }}</td>
                                    <td>{{ $data->merek }}</td>
                                    <td class="text-center">
                                        @if($data->jumlah <= 0)
                                            <span class="text-muted">Belum Ditambahkan</span>
                                        @else
                                            {{ $data->jumlah }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-sm btn-warning edit-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-id="{{ $data->id }}"
                                            data-nama="{{ $data->nama_barang }}"
                                            data-jenis="{{ $data->jenis_barang }}"
                                            data-merek="{{ $data->merek }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Form Hapus -->
                                        <form action="{{ route('databarang.destroy', $data->id) }}" method="POST" class="d-inline delete-confirm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <em>Data belum tersedia.</em>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center">
                            {!! $databarang->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createModalLabel">Tambah Data Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('databarang.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Nama Barang" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Barang</label>
                        <select class="form-control" name="jenis_barang" required>
                            <option value="" disabled selected>Pilih Jenis Barang</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Elektronik">Elektronik</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Merek</label>
                        <input type="text" class="form-control" name="merek" value="{{ old('merek') }}" placeholder="Merek Barang" required>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editModalLabel">Edit Data Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit-id" name="id">

                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" placeholder="Masukan Nama Barang" id="edit-nama" name="nama_barang" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Barang</label>
                        <select class="form-control" id="edit-jenis" name="jenis_barang" required>
                            <option value="">Pilih Jenis Barang</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Elektronik">Elektronik</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Merek</label>
                        <input type="text" class="form-control" placeholder="Masukan Merek" id="edit-merek" name="merek" required>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
