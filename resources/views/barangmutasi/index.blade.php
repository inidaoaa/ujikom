@extends('layouts.index')

@section('content')
@include('sweetalert::alert')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Data Barang Mutasi</h5>
                    <a href="{{ route('barangmutasi.create') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-plus"></i> Tambah Mutasi
                    </a>
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
                                        <a href="{{ route('barangmutasi.edit', $mutasi->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <form action="{{ route('barangmutasi.destroy', $mutasi->id) }}" method="POST" class="d-inline delete-confirm" data-confirm-delete="true">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{route('barangmutasi.destroy', $data->id)}}" type="submit" class="btn btn-sm btn-danger" data-confirm-delete="true">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </form>
                                    </td>
                                </tr>
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
@endsection
