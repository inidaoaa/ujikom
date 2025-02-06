    @extends('layouts.index')

    @section('content')
    @include('sweetalert::alert')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white">Data Barang</h5>
                        <a href="{{ route('databarang.create') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a>
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
                                <thead class="bg-primary text-white">
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
                                                {{ $data->jumlah + $data->pembelians_sum_jumlah }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('databarang.edit', $data->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <form action="{{ route('databarang.destroy', $data->id) }}" method="POST" class="d-inline delete-confirm" data-confirm-delete="true">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
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
    @endsection
