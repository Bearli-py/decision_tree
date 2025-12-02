@extends('layouts.app')

@section('title', 'Decision Tree')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-diagram-2"></i> Decision Tree Visualization</h1>
    <p class="text-muted mb-0">Struktur dan hirarki decision tree yang telah dibangun</p>
</div>

<!-- Tree Info -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Tree</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <strong>Total Training Data:</strong> {{ $stats['total'] }}
            </div>
            <div class="col-md-4">
                <strong>Entropy Awal:</strong> <code>{{ round($stats['entropy_awal'], 3) }}</code>
            </div>
            <div class="col-md-4">
                <strong>Root Node:</strong> <code>PESERTA</code>
            </div>
        </div>
    </div>
</div>

<!-- Tree Structure -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Struktur Tree (ASCII)</h5>
    </div>
    <div class="card-body">
        <pre style="background: #F5F5F5; padding: 1rem; border-radius: 6px; overflow-x: auto;"><code>
                           [PESERTA]
                          /        \
                   < 50 /          \ ≥ 50
                      /              \
              [BUDGET/SPEAKER]    ✅ SUKSES
                  /         \         │
                 /           \        │ (Pure - 5/5)
            Rendah           Tinggi   │
             /                 \      │
            / \                 / \   │
       Biasa Biasa      [BUDGET] [BUDGET]
        |     |           /  \     /  \
      GAGAL GAGAL     Rendah Tinggi Rendah Tinggi
                       |      |     |      |
                     GAGAL  SUKSES GAGAL  SUKSES
        </code></pre>
    </div>
</div>

<!-- Node Details -->
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-diagram-2"></i> Root Node</h5>
            </div>
            <div class="card-body">
                <div class="tree-node">
                    <strong>Node:</strong> PESERTA<br>
                    <strong>Type:</strong> Decision Node<br>
                    <strong>Split:</strong> &lt; 50 vs ≥ 50<br>
                    <strong>Information Gain:</strong> 0.610<br>
                    <strong>Total Data:</strong> 10
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-check-circle"></i> Leaf Node (Pure)</h5>
            </div>
            <div class="card-body">
                <div class="tree-node leaf success">
                    <strong>Condition:</strong> PESERTA ≥ 50<br>
                    <strong>Result:</strong> ✅ SUKSES<br>
                    <strong>Confidence:</strong> 100%<br>
                    <strong>Data Count:</strong> 5/5<br>
                    <strong>Status:</strong> PURE NODE
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Penjelasan -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Penjelasan Tree Building</h5>
    </div>
    <div class="card-body">
        <h6 class="mb-3"><strong>Level 0 (Root):</strong></h6>
        <p>
            Dari 4 atribut yang tersedia (Peserta, Budget, Speaker, Topik), 
            <code>PESERTA</code> dipilih karena memiliki Information Gain tertinggi (0.610).
            Ini berarti split berdasarkan PESERTA paling banyak mengurangi ketidakpastian.
        </p>

        <h6 class="mb-3"><strong>Level 1 - Cabang Kanan (PESERTA ≥ 50):</strong></h6>
        <p>
            Subset ini berisi 5 data, semuanya kelas SUKSES (5/5).
            <strong>Entropy = 0 (Pure Node)</strong>, sehingga tidak perlu split lagi.
            Hasil langsung: <span class="badge badge-success">SUKSES</span>
        </p>

        <h6 class="mb-3"><strong>Level 1 - Cabang Kiri (PESERTA &lt; 50):</strong></h6>
        <p>
            Subset ini berisi 5 data dengan komposisi 1 Sukses dan 4 Gagal.
            <strong>Entropy = 0.722 (Impure)</strong>, masih perlu split lagi.
            Atribut terbaik untuk split selanjutnya adalah SPEAKER atau BUDGET (IG = 0.322).
        </p>
    </div>
</div>
@endsection