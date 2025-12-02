@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h1 class="text-center mb-1">
                        <i class="bi bi-diagram-3"></i> Decision Tree Generator
                    </h1>
                    <p class="text-center text-muted mb-4">Upload file Excel untuk membuat Decision Tree dengan metode ID3</p>

                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Error:</strong>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Upload Form --}}
                    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="file" class="form-label">
                                <strong><i class="bi bi-file-earmark-excel"></i> Pilih File Excel (.xlsx)</strong>
                            </label>
                            <input type="file" class="form-control form-control-lg" id="file" name="file" accept=".xlsx,.xls" required>
                            <small class="text-muted d-block mt-2">
                                File harus memiliki kolom: No, Peserta, Budget, Speaker, Topik, Play
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-cloud-upload"></i> Upload & Process
                        </button>
                    </form>

                    <hr class="my-4">

                    {{-- Info Section --}}
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Format File Excel</h6>
                        <p class="mb-0 small">
                            File Excel harus mengandung kolom:
                            <code>No</code>, <code>Peserta</code>, <code>Budget</code>, <code>Speaker</code>, <code>Topik</code>, <code>Play</code>
                        </p>
                        <p class="mb-0 small mt-2">
                            Contoh nilai:
                            <br>• Peserta: "< 50" atau "≥ 50"
                            <br>• Budget: "Rendah" atau "Tinggi"
                            <br>• Speaker: "Biasa" atau "Expert"
                            <br>• Topik: "Trending" atau "Niche"
                            <br>• Play: "yes" atau "no"
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    .card {
        border: none;
        border-radius: 12px;
    }

    .form-control, .form-control-lg {
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .btn-primary {
        border-radius: 8px;
        background-color: #667eea;
        border: none;
    }

    .btn-primary:hover {
        background-color: #5568d3;
    }
</style>
@endsection