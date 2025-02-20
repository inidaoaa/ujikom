@extends('layouts.index')

@section('content')
@include('sweetalert::alert')
<div class="container mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">Data Barang Pembelian</h5>
                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            </div>

            <div class="card-body">
                <form action="{{ route('pembelian.index') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama atau jenis barang..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-white">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jenis Barang</th>
                                <th>Tahun Pembelian</th>
                                <th>Harga</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse ($pembelians as $data)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $data->databarang ? $data->databarang->nama_barang : 'Nama Barang Tidak Tersedia' }}</td>
                                <td>{{ $data->jenis_barang }}</td>
                                <td>{{ $data->tahun_pembelian }}</td>
                                <td>{{ $data->harga }}</td>
                                <td>{{ $data->keterangan }}</td>
                                <td class="text-center">{{ $data->jumlah }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $data->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('pembelian.destroy', $data->id) }}" method="POST" class="d-inline delete-confirm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" data-confirm-delete="true">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    <em>Data belum tersedia.</em>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $pembelians->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Data Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pembelian.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <select class="form-control" name="id_databarang" required>
                            <option value="" disabled selected>Pilih Nama Barang</option>
                            @foreach($databarang as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Barang</label>
                        <select class="form-control" name="jenis_barang" required>
                            <option value="Furniture">Furniture</option>
                            <option value="Elektronik">Elektronik</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Pembelian</label>
                        <input type="date" class="form-control" name="tahun_pembelian" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="text" class="form-control" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
