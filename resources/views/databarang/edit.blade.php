    {{-- @extends('layouts.index')

    @section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Data Barang</h5>
                        <a href="{{ route('databarang.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('databarang.update', $databarang->id) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                    name="nama_barang" value="{{ $databarang->nama_barang }}" placeholder="Nama Barang" required>
                                @error('nama_barang')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Barang</label>
                                <input type="text" class="form-control @error('jenis_barang') is-invalid @enderror"
                                    name="jenis_barang" value="{{ $databarang->jenis_barang }}" placeholder="Jenis Barang" required>
                                @error('jenis_barang')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Merek</label>
                                <input type="text" class="form-control @error('merek') is-invalid @enderror"
                                    name="merek" value="{{ $databarang->merek }}" placeholder="Merek Barang" required>
                                @error('merek')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>


                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection --}}
