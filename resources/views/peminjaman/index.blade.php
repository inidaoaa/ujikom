@extends('layouts.index')

@section('content')
    @include('sweetalert::alert')
    <div class="container mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Data Peminjaman</h5>
                    <a href="{{ route('peminjaman.create') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('peminjaman.index') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari berdasarkan nama atau jenis barang..." value="{{ request('search') }}">
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
                                        <td>{{ $data->dataBarang ? $data->dataBarang->nama_barang : 'Barang Tidak Ditemukan' }}
                                        </td>
                                        <td>{{ $data->jenis_barang }}</td>
                                        <td>{{ $data->tanggal_pinjam }}</td>
                                        <td>{{ $data->tanggal_kembali }}</td>
                                        <td>{{ $data->lokasi_awal }}</td>
                                        <td>{{ $data->lokasi_pinjam }}</td>
                                        <td>{{ $data->ruangan }}</td>
                                        <td class="text-center">{{ $data->jumlah }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-{{ $data->status == 'dipinjam' ? 'warning' : 'success' }}">
                                                {{ ucfirst($data->status) }}
                                            </span>

                                        </td>
                                        <td>
                                            <div class="row">
                                                {{-- <div class="col d-flex col-sm-6">
                                                    <a href="{{ route('peminjaman.edit', $data->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div> --}}
                                                <div class="col d-flex col-sm-6">
                                                    <form action="{{ route('peminjaman.destroy', $data->id) }}"
                                                        method="POST" class="d-inline delete-confirm"
                                                        data-id="{{ $data->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a type="submit" class="btn btn-sm btn-danger" data-confirm-delete="true">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
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
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
@endsection
