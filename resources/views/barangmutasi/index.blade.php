@extends('layouts.index')

@section('content')
@include('sweetalert::alert')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Data Barang Mutasi</h5>
                    <!-- Tombol Tambah Mutasi untuk Membuka Modal -->
                    <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> Tambah Mutasi
                    </button>
                </div>

                <div class="card-body">
                    <!-- Form Pencarian -->
                    <form action="{{ route('barangmutasi.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari Barang Mutasi..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                    <!-- End Form Pencarian -->

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="text-white">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jenis Barang</th>
                                    <th>Tanggal Mutasi</th>
                                    <th>Lokasi Awal</th>
                                    <th>Lokasi Mutasi</th>
                                    <th>Ruangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse ($barangMutasi as $mutasi)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $mutasi->databarang ? $mutasi->databarang->nama_barang : 'Nama Barang Tidak Tersedia' }}</td>
                                    <td>{{ $mutasi->jenis_barang }}</td>
                                    <td>{{ $mutasi->tanggal_mutasi }}</td>
                                    <td>{{ $mutasi->lokasi_awal }}</td>
                                    <td>{{ $mutasi->lokasi_mutasi }}</td>
                                    <td>{{ $mutasi->ruangan }}</td>
                                    <td class="text-center">
                                        <!-- Tombol Edit untuk Membuka Modal -->
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $mutasi->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('barangmutasi.destroy', $mutasi->id) }}" method="POST" class="d-inline delete-confirm" data-confirm-delete="true">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal{{ $mutasi->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $mutasi->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="editModalLabel{{ $mutasi->id }}">Edit Barang Mutasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('barangmutasi.update', $mutasi->id) }}" method="POST" class="row g-3">
                                                    @csrf
                                                    @method('PUT')

                                                    <!-- Nama Barang -->
                                                    <div class="col-md-6">
                                                        <label for="nama_barang" class="form-label">Nama Barang</label>
                                                        <select class="form-control" name="id_databarang" required>
                                                            <option value="" selected disabled>Pilih Nama Barang</option>
                                                            @foreach($databarang as $barang)
                                                                <option value="{{ $barang->id }}" {{ $mutasi->id_databarang == $barang->id ? 'selected' : '' }}>
                                                                    {{ $barang->nama_barang }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Jenis Barang -->
                                                    <div class="col-md-6">
                                                        <label for="jenis_barang" class="form-label">Jenis Barang</label>
                                                        <select class="form-control" name="jenis_barang" required>
                                                            <option value="Furniture" {{ $mutasi->jenis_barang == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                                            <option value="Elektronik" {{ $mutasi->jenis_barang == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                                        </select>
                                                    </div>

                                                    <!-- Tanggal Mutasi -->
                                                    <div class="col-md-6">
                                                        <label for="tanggal_mutasi" class="form-label">Tanggal Mutasi</label>
                                                        <input type="date" class="form-control" name="tanggal_mutasi" value="{{ $mutasi->tanggal_mutasi }}" required>
                                                    </div>

                                                    <!-- Lokasi Awal -->
                                                    <div class="col-md-6">
                                                        <label for="lokasi_awal" class="form-label">Lokasi Awal</label>
                                                        <input type="text" class="form-control" name="lokasi_awal" value="{{ $mutasi->lokasi_awal }}" required>
                                                    </div>

                                                    <!-- Lokasi Mutasi -->
                                                    <div class="col-md-6">
                                                        <label for="lokasi_mutasi" class="form-label">Lokasi Mutasi</label>
                                                        <input type="text" class="form-control" name="lokasi_mutasi" value="{{ $mutasi->lokasi_mutasi }}" required>
                                                    </div>

                                                    <!-- Ruangan -->
                                                    <div class="col-md-6">
                                                        <label for="ruangan" class="form-label">Ruangan</label>
                                                        <input type="text" class="form-control" name="ruangan" value="{{ $mutasi->ruangan }}" required>
                                                    </div>

                                                    <!-- Lantai -->
                                                    <div class="col-md-6">
                                                        <label for="lantai" class="form-label">Lantai</label>
                                                        <input type="text" class="form-control" name="lantai" value="{{ $mutasi->lantai }}" required>
                                                    </div>

                                                    <!-- Keterangan -->
                                                    <div class="col-md-6">
                                                        <label for="keterangan" class="form-label">Keterangan</label>
                                                        <input type="text" class="form-control" name="keterangan" value="{{ $mutasi->keterangan }}">
                                                    </div>

                                                    <!-- Tombol Submit -->
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <em>Data mutasi belum tersedia.</em>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center">
                            {!! $barangMutasi->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
                        </div>
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
                <h5 class="modal-title" id="createModalLabel">Tambah Barang Mutasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('barangmutasi.store') }}" method="POST" class="row g-3">
                    @csrf

                    <!-- Nama Barang -->
                    <div class="col-md-6">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <select class="form-control" name="id_databarang" required>
                            <option value="" selected disabled>Pilih Nama Barang</option>
                            @foreach($databarang as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Barang -->
                    <div class="col-md-6">
                        <label for="jenis_barang" class="form-label">Jenis Barang</label>
                        <select class="form-control" name="jenis_barang" required>
                            <option value="Furniture">Furniture</option>
                            <option value="Elektronik">Elektronik</option>
                        </select>
                    </div>

                    <!-- Tanggal Mutasi -->
                    <div class="col-md-6">
                        <label for="tanggal_mutasi" class="form-label">Tanggal Mutasi</label>
                        <input type="date" class="form-control" name="tanggal_mutasi" required>
                    </div>

                    <!-- Lokasi Awal -->
                    <div class="col-md-6">
                        <label for="lokasi_awal" class="form-label">Lokasi Awal</label>
                        <input type="text" class="form-control" name="lokasi_awal" required>
                    </div>

                    <!-- Lokasi Mutasi -->
                    <div class="col-md-6">
                        <label for="lokasi_mutasi" class="form-label">Lokasi Mutasi</label>
                        <input type="text" class="form-control" name="lokasi_mutasi" required>
                    </div>

                    <!-- Ruangan -->
                    <div class="col-md-6">
                        <label for="ruangan" class="form-label">Ruangan</label>
                        <input type="text" class="form-control" name="ruangan" required>
                    </div>

                    <!-- Lantai -->
                    <div class="col-md-6">
                        <label for="lantai" class="form-label">Lantai</label>
                        <input type="text" class="form-control" name="lantai" required>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan">
                    </div>

                    <!-- Tombol Submit -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
