    @extends('layouts.index')

    @section('content')
    @include('sweetalert::alert')
    <div class="container mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Data Barang Pembelian</h5>
                    <a href="{{ route('pembelian.create') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                </div>

                    <div class="card-body">
                        <!-- Form Pencarian -->
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
                                        <td>{{ $data->keterangan}}</td>
                                        <td class="text-center">{{ $data->jumlah }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('pembelian.edit', $data->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('pembelian.destroy', $data->id) }}" method="POST" class="d-inline delete-confirm" data-id="{{ $data->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{route('pembelian.destroy', $data->id)}}" type="submit" class="btn btn-sm btn-danger" data-confirm-delete="true">
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
                                {!! $pembelians->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
                            </div>
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

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session("success") }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif
    @endsection
