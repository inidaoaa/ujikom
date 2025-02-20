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
                    <a href="{{ route('barangmusnah.create') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
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
                                        <form action="{{ route('barangmusnah.destroy', $data->id) }}" method="POST" class="d-inline delete-confirm">
                                            @csrf
                                            @method('DELETE')
                                            <a type="submit" class="btn btn-sm btn-danger" data-confirm-delete="true">
                                                <i class="fas fa-trash"></i> 
                                            </a>
                                        </form>
                                    </td>
                                </tr>
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
@endsection
