@extends('layouts.app')

@section('title', 'Upload Dataset')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h1 class="text-center mb-1">
                        Decision Tree Generator
                    </h1>
                    <p class="text-center text-muted mb-4">Upload file Excel untuk membuat Decision Tree</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="file" class="form-label">
                                <strong>Pilih File Excel (.xlsx)</strong>
                            </label>
                            <input type="file" class="form-control form-control-lg" id="file" name="file" accept=".xlsx,.xls" required>
                            <small class="text-muted d-block mt-2">
                                File harus memiliki kolom: No, Peserta, Budget, Speaker, Topik, Play
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Upload & Process
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="alert alert-info">
                        <h6>Format File Excel</h6>
                        <p class="mb-0 small">
                            Kolom: No, Peserta, Budget, Speaker, Topik, Play<br>
                            Peserta: "< 50" atau "≥ 50"<br>
                            Budget: "Rendah" atau "Tinggi"<br>
                            Speaker: "Biasa" atau "Expert"<br>
                            Topik: "Trending" atau "Niche"<br>
                            Play: "yes" atau "no"
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection