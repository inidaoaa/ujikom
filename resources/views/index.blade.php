@extends('layouts.index')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-center">📊 Dashboard Inventaris</h1>

    {{-- MINI CARDS --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5 g-4 mb-5">
    @foreach ($cards as $card)
        <div class="col">
            <a href="{{ $card['route'] }}" class="text-decoration-none text-dark" style="background-color: #4e73df;">
                <div class="card h-100 shadow-sm border-0 rounded-4 text-center">
                    <div class="card-body">
                        <h5 class="card-title">{{ $card['icon'] }} {{ $card['title'] }}</h5>
                        <h3 class="fw-semibold">
                            @if ($card['value'] > 0)
                                {{ $card['value'] }}
                            @else
                                😴 Tidak ada
                            @endif
                        </h3>
                        <p class="text-muted small">{{ $card['subtitle'] }}</p>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

{{-- Aktivitas Terbaru --}}
<div class="card shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <h5 class="card-title">🕓 Aktivitas Terbaru</h5>

        @if ($recentActivities->isNotEmpty())
            <ul class="list-group list-group-flush">
                @foreach ($recentActivities as $activity)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{!! $activity['icon'] !!} {{ $activity['text'] }}</span>
                        <span class="badge bg-secondary rounded-pill">{{ $activity['time'] }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center text-muted">Belum ada aktivitas yang dilakukan.</div>
        @endif
    </div>
</div>


<div class="text-center mt-5 text-muted fst-italic">
    "Web Inventaris barang"
</div>


</div>
@endsection
