@extends('layouts.app')

@section('title', 'Prediction')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-magic"></i> Prediksi Kesuksesan Event</h1>
    <p class="text-muted mb-0">Masukkan parameter event dan sistem akan memprediksi kesuksesannya</p>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Form Prediksi</h5>
            </div>
            <div class="card-body">
                <form id="predictionForm">
                    @csrf

                    <div class="mb-3">
                        <label for="peserta" class="form-label">
                            <i class="bi bi-people"></i> Jumlah Peserta
                        </label>
                        <select class="form-select" id="peserta" name="peserta" required>
                            <option value="">-- Pilih --</option>
                            <option value="< 50">Kurang dari 50 orang</option>
                            <option value=">= 50">50 orang atau lebih</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="budget" class="form-label">
                            <i class="bi bi-cash-coin"></i> Budget
                        </label>
                        <select class="form-select" id="budget" name="budget" required>
                            <option value="">-- Pilih --</option>
                            <option value="Rendah">Budget Rendah</option>
                            <option value="Tinggi">Budget Tinggi</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="speaker" class="form-label">
                            <i class="bi bi-mic"></i> Kualitas Speaker
                        </label>
                        <select class="form-select" id="speaker" name="speaker" required>
                            <option value="">-- Pilih --</option>
                            <option value="Biasa">Speaker Biasa Saja</option>
                            <option value="Expert">Speaker Expert/Ahli</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="topik" class="form-label">
                            <i class="bi bi-tags"></i> Topik Event
                        </label>
                        <select class="form-select" id="topik" name="topik" required>
                            <option value="">-- Pilih --</option>
                            <option value="Trending">Topik Trending/Populer</option>
                            <option value="Niche">Topik Niche/Khusus</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-lightning-charge"></i> PREDIKSI SEKARANG
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div id="resultContainer" style="display: none;">
            <!-- Hasil akan muncul di sini via JavaScript -->
        </div>

        <!-- Contoh Penggunaan -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-question-circle"></i> Contoh Input</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-success"><i class="bi bi-check-circle"></i> Contoh Sukses</h6>
                    <small class="text-muted">
                        <strong>Peserta:</strong> ≥ 50<br>
                        <strong>Budget:</strong> Tinggi<br>
                        <strong>Speaker:</strong> Expert<br>
                        <strong>Topik:</strong> Trending<br>
                        <strong class="text-success">Hasil: SUKSES ✅</strong>
                    </small>
                </div>

                <hr>

                <div>
                    <h6 class="text-danger"><i class="bi bi-x-circle"></i> Contoh Gagal</h6>
                    <small class="text-muted">
                        <strong>Peserta:</strong> < 50<br>
                        <strong>Budget:</strong> Rendah<br>
                        <strong>Speaker:</strong> Biasa<br>
                        <strong>Topik:</strong> Trending<br>
                        <strong class="text-danger">Hasil: GAGAL ❌</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Panduan Decision Path -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-diagram-2"></i> Panduan Decision Path</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">Sistem akan mengikuti path di decision tree berdasarkan input Anda:</p>
        <ol>
            <li><strong>Step 1:</strong> Cek atribut PESERTA
                <ul>
                    <li>Jika <code>≥ 50</code> → Langsung hasil <span class="badge badge-success">SUKSES</span></li>
                    <li>Jika <code>&lt; 50</code> → Lanjut ke Step 2</li>
                </ul>
            </li>
            <li><strong>Step 2:</strong> Cek atribut SPEAKER (untuk peserta &lt; 50)
                <ul>
                    <li>Jika <code>Biasa</code> → Hasil <span class="badge badge-danger">GAGAL</span></li>
                    <li>Jika <code>Expert</code> → Lanjut ke Step 3</li>
                </ul>
            </li>
            <li><strong>Step 3:</strong> Cek atribut BUDGET (untuk peserta &lt; 50 dan speaker Expert)
                <ul>
                    <li>Jika <code>Rendah</code> → Hasil <span class="badge badge-danger">GAGAL</span></li>
                    <li>Jika <code>Tinggi</code> → Hasil <span class="badge badge-success">SUKSES</span></li>
                </ul>
            </li>
        </ol>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('predictionForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const peserta = document.getElementById('peserta').value;
    const budget = document.getElementById('budget').value;
    const speaker = document.getElementById('speaker').value;
    const topik = document.getElementById('topik').value;

    // Validasi
    if (!peserta || !budget || !speaker || !topik) {
        alert('Semua field harus diisi!');
        return;
    }

    // Convert >= 50 format
    const pesertaValue = peserta === '>= 50' ? '≥ 50' : '< 50';

    try {
        const response = await axios.post('{{ route("doPrediction") }}', {
            peserta: pesertaValue,
            budget: budget,
            speaker: speaker,
            topik: topik,
            _token: document.querySelector('input[name="_token"]').value
        });

        const data = response.data;
        displayResult(data);
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat prediksi!');
    }
});

function displayResult(data) {
    const container = document.getElementById('resultContainer');
    const isSuccess = data.hasil === 'Sukses';
    
    let pathHTML = '';
    if (data.path && data.path.length > 0) {
        pathHTML = '<div class="path-list">';
        data.path.forEach((item, index) => {
            pathHTML += `<div class="path-item">
                <strong>${index + 1}.</strong> ${item}
            </div>`;
        });
        pathHTML += '</div>';
    }

    const html = `
        <div class="result-box ${isSuccess ? 'success' : 'fail'}">
            <div class="result-title">
                ${isSuccess ? '✅ SUKSES' : '❌ GAGAL'}
            </div>
            
            <div class="mb-3">
                <h6>Parameter Input:</h6>
                <div class="bg-light p-2 rounded" style="font-size: 0.9rem;">
                    <strong>Peserta:</strong> ${data.input.peserta}<br>
                    <strong>Budget:</strong> ${data.input.budget}<br>
                    <strong>Speaker:</strong> ${data.input.speaker}<br>
                    <strong>Topik:</strong> ${data.input.topik}
                </div>
            </div>

            <h6>Decision Path:</h6>
            ${pathHTML}

            <div class="mt-3 pt-3 border-top">
                <small class="text-muted">
                    <i class="bi bi-shield-check"></i> 
                    Confidence: <strong>${data.confidence}</strong>
                </small>
            </div>
        </div>
    `;

    container.innerHTML = html;
    container.style.display = 'block';
    
    // Scroll to result
    container.scrollIntoView({ behavior: 'smooth' });
}
</script>
@endpush