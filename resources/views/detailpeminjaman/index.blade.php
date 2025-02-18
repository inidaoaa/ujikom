@extends('layouts.index')

@section('content')
<div class="container mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">Detail Peminjaman</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-white">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Status Pengembalian</th>
                                <th>Tanggal Dibuat</th>
                                <th>Tanggal Diupdate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse ($detailPeminjaman as $detail)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $detail->peminjaman->nama_peminjam }}</td>
                                <td>{{ $detail->dataBarang->nama_barang }}</td>
                                <td class="text-center">{{ $detail->jumlah }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $detail->status_pengembalian == 'belum' ? 'warning' : 'success' }}">
                                        {{ ucfirst($detail->status_pengembalian) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($detail->created_at)->format('d-m-Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($detail->updated_at)->format('d-m-Y H:i') }}</td>
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
                        {!! $detailPeminjaman->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
