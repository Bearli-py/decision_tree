@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 700px; margin: 3rem auto;">
    <h1 style="font-size: 2.2rem; margin-bottom: 0.5rem;">Upload Dataset</h1>
    <p style="color: #718096; margin-bottom: 2rem; font-size: 0.95rem;">
        Unggah file CSV untuk analisis decision tree otomatis dengan perhitungan entropy & information gain.
    </p>

    @if ($errors->any())
        <div class="error">
            <strong>⚠️ Error:</strong>
            <ul style="margin-top: 0.5rem; list-style: inside;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <label>📄 File CSV:</label>
        <input type="file" name="file" accept=".csv,text/csv" required style="cursor: pointer;">
        <small style="color: #a0aec0; display: block; margin-top: 0.5rem;">
            Format: CSV dengan header kolom yang jelas
        </small>

        <label>🎯 Target Column:</label>
        <input type="text" name="target_column" value="Play" placeholder="Contoh: Play, Beli, Label" required>
        <small style="color: #a0aec0; display: block; margin-top: 0.5rem;">
            Nama kolom yang menjadi target klasifikasi
        </small>

        <button type="submit">🚀 Analisis Sekarang</button>
    </form>
</div>
@endsection