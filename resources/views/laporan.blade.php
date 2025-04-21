@extends('layouts.index')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Laporan Ringkasan per Bulan</h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-funnel-fill"></i> Filter Laporan
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filter Modal -->
                    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">Filter Laporan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form method="GET" action="{{ route('laporan.index') }}">
                            <div class="modal-body">
                              <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select name="tahun" id="tahun" class="form-select">
                                  <option value="">Semua Tahun</option>
                                  @for ($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                  @endfor
                                </select>
                              </div>
                              <div class="mb-3">
                                <label for="bulan" class="form-label">Bulan</label>
                                <select name="bulan" id="bulan" class="form-select">
                                  <option value="">Semua Bulan</option>
                                  @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                                  @endfor
                                </select>
                              </div>
                              {{-- <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control" min="1" max="31" value="{{ request('tanggal') }}">
                              </div> --}}
                              {{-- <div class="mb-3">
                                <label for="hari" class="form-label">Hari</label>
                                <select name="hari" id="hari" class="form-select">
                                  <option value="">Semua Hari</option>
                                  @foreach (['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'] as $en => $id)
                                    <option value="{{ $en }}" {{ request('hari') == $en ? 'selected' : '' }}>{{ $id }}</option>
                                  @endforeach
                                </select>
                              </div> --}}
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                              <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- Tabel Laporan -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th>Pembelian</th>
                                    <th>Peminjaman</th>
                                    <th>Pengembalian</th>
                                    <th>Musnah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!request()->filled('tahun') && !request()->filled('bulan') && !request()->filled('tanggal') && !request()->filled('hari'))
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">Data belum di filter.</td>
                                    </tr>
                                @else
                                    @php $no = 1; @endphp
                                    @forelse ($laporan as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ \Carbon\Carbon::create()->month($item['bulan'])->translatedFormat('F') }}</td>
                                            <td>{{ $item['tahun'] ?? '-' }}</td>
                                            <td>{{ $item['pembelian'] }}</td>
                                            <td>{{ $item['peminjaman'] }}</td>
                                            <td>{{ $item['pengembalian'] }}</td>
                                            <td>{{ $item['barang_musnah'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Tidak ada data sesuai filter.</td>
                                        </tr>
                                    @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
