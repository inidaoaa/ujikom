@extends('layouts.index')

@section('content')
@include('sweetalert::alert')
<div class="container mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">Detail Pengembalian</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('pengembalian.index') }}" method="GET" class="mb-3">
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
                    {{-- <div class="d-flex justify-content-center">
                        {!! $pengembalian->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
