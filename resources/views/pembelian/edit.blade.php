@extends('layouts.index')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Data Pembelian</h5>
                    <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
                        @method('PUT')
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Pilih Data Barang</label>
                            <select class="form-select @error('id_databarang') is-invalid @enderror" name="id_databarang" required>
                                <option value="">Pilih Barang</option>
                                @foreach($databarang as $barang)
                                    <option value="{{ $barang->id }}" {{ $pembelian->id_databarang == $barang->id ? 'selected' : '' }}>
                                        {{ $barang->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_data_barang')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Barang</label>
                            <select class="form-select @error('jenis_barang') is-invalid @enderror" name="jenis_barang" required>
                                <option value="Furniture" {{ $pembelian->jenis_barang == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                <option value="Elektronik" {{ $pembelian->jenis_barang == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            </select>
                            @error('jenis_barang')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tahun Pembelian</label>
                            <input type="text" class="form-control @error('tahun_pembelian') is-invalid @enderror"
                                name="tahun_pembelian" value="{{ $pembelian->tahun_pembelian }}" placeholder="Tahun Pembelian" required>
                            @error('tahun_pembelian')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="text" class="form-control @error('harga') is-invalid @enderror"
                                name="harga" value="{{ $pembelian->harga }}" placeholder="Harga Barang" required>
                            @error('harga')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                name="jumlah" value="{{ $pembelian->jumlah }}" placeholder="Jumlah Barang" required>
                            @error('jumlah')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" placeholder="Keterangan">{{ $pembelian->keterangan }}</textarea>
                            @error('keterangan')
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
@endsection
