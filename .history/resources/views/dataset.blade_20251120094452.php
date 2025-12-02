@extends('layouts.app')

@section('title', 'Dataset')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-table"></i> Dataset Training</h1>
    <p class="text-muted mb-0">Data yang digunakan untuk melatih decision tree</p>
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Event</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value" style="color: var(--success-color);">{{ $stats['sukses'] }}</div>
            <div class="stat-label">Sukses</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value" style="color: var(--danger-color);">{{ $stats['gagal'] }}</div>
            <div class="stat-label">Gagal</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value">{{ round($stats['entropy_awal'], 3) }}</div>
            <div class="stat-label">Entropy Awal</div>
        </div>
    </div>
</div>

<!-- Tabel Data -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Data Training</h5>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peserta</th>
                    <th>Budget</th>
                    <th>Speaker</th>
                    <th>Topik</th>
                    <th>Hasil</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
                        <td>{{ $item->peserta }}</td>
                        <td>{{ $item->budget }}</td>
                        <td>{{ $item->speaker }}</td>
                        <td>{{ $item->topik }}</td>
                        <td>
                            @if($item->hasil === 'Sukses')
                                <span class="badge badge-success"><i class="bi bi-check-circle"></i> {{ $item->hasil }}</span>
                            @else
                                <span class="badge badge-danger"><i class="bi bi-x-circle"></i> {{ $item->hasil }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                            Belum ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Info -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Penjelasan Atribut</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <h6><strong>Peserta:</strong></h6>
                <ul class="small">
                    <li><code>&lt; 50</code> - Peserta kurang dari 50 orang</li>
                    <li><code>≥ 50</code> - Peserta 50 orang atau lebih</li>
                </ul>
            </div>
            <div class="col-md-6 mb-3">
                <h6><strong>Budget:</strong></h6>
                <ul class="small">
                    <li><code>Rendah</code> - Budget terbatas</li>
                    <li><code>Tinggi</code> - Budget mencukupi</li>
                </ul>
            </div>
            <div class="col-md-6 mb-3">
                <h6><strong>Speaker:</strong></h6>
                <ul class="small">
                    <li><code>Biasa</code> - Speaker biasa saja</li>
                    <li><code>Expert</code> - Speaker ahli di bidangnya</li>
                </ul>
            </div>
            <div class="col-md-6 mb-3">
                <h6><strong>Topik:</strong></h6>
                <ul class="small">
                    <li><code>Trending</code> - Topik sedang trending/populer</li>
                    <li><code>Niche</code> - Topik khusus/spesifik</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Distribution -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Distribusi Hasil</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Persentase</h6>
                <div class="progress mb-2" style="height: 30px;">
                    <div class="progress mb-2" style="height: 30px;">
                        Sukses: {{ round(($stats['sukses'] / $stats['total']) * 100) }}%
                    </div>
                </div>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(($stats['sukses'] / $stats['total']) * 100, 2) }}%;">
                        Gagal: {{ round(($stats['gagal'] / $stats['total']) * 100) }}%
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h6>Statistik</h6>
                <ul class="small">
                    <li><strong>Total Event:</strong> {{ $stats['total'] }}</li>
                    <li><strong>Sukses:</strong> {{ $stats['sukses'] }} event</li>
                    <li><strong>Gagal:</strong> {{ $stats['gagal'] }} event</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection