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
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Data Peminjaman</h5>
                    <!-- Tombol Tambah Data untuk Membuka Modal -->
                    <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>

                <div class="card-body">
                    <!-- Form Pencarian -->
                    <form action="{{ route('peminjaman.index') }}" method="GET" class="mb-3">
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
                                    <th>Lokasi Pinjaman</th>
                                    <th>Ruangan</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse ($peminjaman as $data)
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
                                        <span class="badge bg-{{ $data->status == 'dipinjam' ? 'warning' : 'success' }}">
                                            {{ ucfirst($data->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <!-- Tombol Edit untuk Membuka Modal -->
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $data->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('peminjaman.destroy', $data->id) }}" method="POST" class="d-inline delete-confirm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal{{ $data->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $data->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="editModalLabel{{ $data->id }}">Edit Peminjaman</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('peminjaman.update', $data->id) }}" method="POST" class="row g-3">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="col-md-6">
                                                            <label class="form-label">Nama Peminjam</label>
                                                            <input type="text" name="nama_peminjam" class="form-control" value="{{ $data->nama_peminjam }}" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Nama Barang</label>
                                                            <select name="id_databarang" class="form-control" required>
                                                                <option value="" disabled>Pilih Nama Barang</option>
                                                                @foreach($databarang as $barang)
                                                                    <option value="{{ $barang->id }}" {{ $data->id_databarang == $barang->id ? 'selected' : '' }}>
                                                                        {{ $barang->nama_barang }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Jenis Barang</label>
                                                            <select name="jenis_barang" class="form-control" required>
                                                                <option value="">Pilih Jenis Barang</option>
                                                                <option value="Furniture" {{ $data->jenis_barang == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                                                <option value="Elektronik" {{ $data->jenis_barang == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Tanggal Pinjam</label>
                                                            <input type="date" name="tanggal_pinjam" class="form-control" value="{{ $data->tanggal_pinjam }}" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Tanggal Kembali</label>
                                                            <input type="date" name="tanggal_kembali" class="form-control" value="{{ $data->tanggal_kembali }}" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Lokasi Awal</label>
                                                            <input type="text" name="lokasi_awal" class="form-control" value="{{ $data->lokasi_awal }}" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Lokasi Pinjam</label>
                                                            <input type="text" name="lokasi_pinjam" class="form-control" value="{{ $data->lokasi_pinjam }}" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Ruangan</label>
                                                            <input type="text" name="ruangan" class="form-control" value="{{ $data->ruangan }}" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Jumlah</label>
                                                            <input type="number" name="jumlah" class="form-control" value="{{ $data->jumlah }}" required>
                                                        </div>

                                                        <div class="col-12 text-end">
                                                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted">
                                        <em>Data belum tersedia.</em>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center">
                            {!! $peminjaman->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
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
                <h5 class="modal-title" id="createModalLabel">Tambah Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('peminjaman.store') }}" method="POST" class="row g-3">
                    @csrf

                    <!-- Nama Peminjam -->
                    <div class="col-md-6">
                        <label class="form-label">Nama Peminjam</label>
                        <input type="text" class="form-control" placeholder="Masukan Nama Peminjam" name="nama_peminjam" required>
                    </div>

                    <!-- Nama Barang -->
                    <div class="col-md-6">
                        <label class="form-label">Nama Barang</label>
                        <select class="form-control" name="id_databarang" required>
                            <option value="" selected disabled>Pilih Nama Barang</option>
                            @foreach($databarang as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Barang -->
                    <div class="col-md-6">
                        <label class="form-label">Jenis Barang</label>
                        <select class="form-control" name="jenis_barang" required>
                            <option value="">Pilih Jenis Barang</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Elektronik">Elektronik</option>
                        </select>
                    </div>

                    <!-- Tanggal Pinjam -->
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="date" class="form-control" placeholder="Masukan Tanggal Pinjam" name="tanggal_pinjam" required>
                    </div>

                    <!-- Tanggal Kembali -->
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Kembali</label>
                        <input type="date" class="form-control" placeholder="Masukan Tanggal kembali" name="tanggal_kembali" required>
                    </div>

                    <!-- Lokasi Awal -->
                    <div class="col-md-6">
                        <label class="form-label">Lokasi Awal</label>
                        <input type="text" class="form-control" placeholder="Masukan Lokasi Awal" name="lokasi_awal" required>
                    </div>

                    <!-- Lokasi Pinjam -->
                    <div class="col-md-6">
                        <label class="form-label">Lokasi Pinjam</label>
                        <input type="text" class="form-control" placeholder="Masukan Lokasi Pinjmanan" name="lokasi_pinjam" required>
                    </div>

                    <!-- Ruangan -->
                    <div class="col-md-6">
                        <label class="form-label">Ruangan</label>
                        <input type="text" class="form-control" placeholder="Masukan Nama Ruangan" name="ruangan" required>
                    </div>

                    <!-- Jumlah -->
                    <div class="col-md-6">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" placeholder="Isi Jumlah" name="jumlah" required>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Script untuk menampilkan SweetAlert jika ada session 'success'
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Script untuk menampilkan SweetAlert jika ada session 'error'
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Konfirmasi Hapus dengan SweetAlert
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.delete-confirm').forEach(function(button) {
            button.addEventListener('submit', function(event) {
                event.preventDefault();
                let form = this;
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
