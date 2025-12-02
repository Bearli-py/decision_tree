@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-diagram-3"></i> Decision Tree - Prediksi Kesuksesan Event/Workshop</h1>
    <p class="text-muted mb-0">Sistem prediksi kesuksesan event berdasarkan Decision Tree Algorithm</p>
</div>

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Tentang Aplikasi</h5>
                <p class="card-text">
                    Aplikasi ini menggunakan algoritma Decision Tree ID3 untuk memprediksi apakah sebuah event/workshop akan sukses atau gagal berdasarkan beberapa parameter kunci:
                </p>
                <ul>
                    <li><strong>Peserta:</strong> Jumlah peserta yang diharapkan</li>
                    <li><strong>Budget:</strong> Besarnya budget event</li>
                    <li><strong>Speaker:</strong> Kualitas pembicara</li>
                    <li><strong>Topik:</strong> Jenis topik event</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-gear"></i> Cara Kerja</h5>
                <ol>
                    <li>Dataset training terdiri dari 10 event dengan hasil terverifikasi</li>
                    <li>Sistem menghitung Entropy dan Information Gain setiap atribut</li>
                    <li>Decision Tree dibangun dengan pemilihan split terbaik di setiap level</li>
                    <li>Model siap untuk memprediksi event baru</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-table" style="font-size: 2rem; color: var(--primary-color);"></i>
                <h5 class="mt-3">Dataset</h5>
                <p class="text-muted">Lihat data training</p>
                <a href="{{ route('dataset') }}" class="btn btn-sm btn-primary">Lihat <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-diagram-2" style="font-size: 2rem; color: var(--primary-color);"></i>
                <h5 class="mt-3">Tree</h5>
                <p class="text-muted">Struktur tree</p>
                <a href="{{ route('tree') }}" class="btn btn-sm btn-primary">Lihat <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-magic" style="font-size: 2rem; color: var(--primary-color);"></i>
                <h5 class="mt-3">Prediksi</h5>
                <p class="text-muted">Prediksi event baru</p>
                <a href="{{ route('predict') }}" class="btn btn-sm btn-primary">Mulai <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-check2-all" style="font-size: 2rem; color: var(--primary-color);"></i>
                <h5 class="mt-3">Verifikasi</h5>
                <p class="text-muted">Bandingkan hasil</p>
                <a href="{{ route('comparison') }}" class="btn btn-sm btn-primary">Lihat <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Informasi Teknis</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Algoritma: ID3 (Iterative Dichotomiser 3)</h6>
                <p class="text-muted small">
                    Menggunakan Information Gain berbasis Entropy untuk memilih atribut terbaik pada setiap node.
                </p>
            </div>
            <div class="col-md-6">
                <h6>Kriteria Split: Information Gain Maksimal</h6>
                <p class="text-muted small">
                    Atribut dengan IG tertinggi dipilih untuk mengurangi ketidakpastian maksimal.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection