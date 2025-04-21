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
                    <h5 class="mb-0 text-white">Data Barang Musnah</h5>
                    <!-- Tombol Tambah Data untuk Membuka Modal -->
                    <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>

                <div class="card-body">
                    <!-- Form Pencarian -->
                    <form action="{{ route('barangmusnah.index') }}" method="GET" class="mb-3">
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
                            <thead class="bg-white text-white">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jenis Barang</th>
                                    <th>Tanggal Pemusnahan</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse ($barangMusnah as $data)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $data->databarang ? $data->databarang->nama_barang : 'Nama Barang Tidak Tersedia' }}</td>
                                    <td>{{ $data->jenis_barang }}</td>
                                    <td>{{ $data->tanggal_pemusnahan }}</td>
                                    <td class="text-center">{{ $data->jumlah }}</td>
                                    <td>{{ $data->keterangan }}</td>
                                    <td class="text-center">
                                        <!-- Tombol Edit untuk Membuka Modal -->
                                        {{-- <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $data->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button> --}}

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('barangmusnah.destroy', $data->id) }}" method="POST" class="d-inline delete-confirm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <em>Data belum tersedia.</em>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center">
                            {!! $barangMusnah->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
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
                <h5 class="modal-title" id="createModalLabel">Tambah Barang Musnah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('barangmusnah.store') }}" method="POST" class="row g-3">
                    @csrf

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

                    <!-- Tanggal Pemusnahan -->
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Pemusnahan</label>
                        <input type="date" class="form-control" name="tanggal_pemusnahan" required>
                    </div>

                    <!-- Jumlah -->
                    <div class="col-md-6">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" placeholder="Masukan Jumlah" name="jumlah" required>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-md-12">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" placeholder="isi keterangan" name="keterangan"></textarea>
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

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: '{!! implode("<br>", $errors->all()) !!}',
        });
    </script>
@endif
