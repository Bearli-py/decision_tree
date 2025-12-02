@extends('layouts.app')

@section('content')
<div class="card">
    <h1 style="margin-bottom: 1rem;">Hasil: {{ $fileName ?? 'Dataset' }}</h1>
    <p>Target: <strong style="color: #d4a017;">{{ $metrics['target'] ?? '?' }}</strong></p>
    <p>Total Data: <strong>{{ $metrics['total_data'] ?? 0 }}</strong></p>
    <p>Entropy: <strong>{{ number_format($metrics['entropy'] ?? 0, 4) }}</strong></p>
</div>

@if(!empty($metrics['attributes']))
<div class="card">
    <h2 style="margin-bottom: 1rem;">Atribut & Gain Ratio</h2>
    @foreach($metrics['attributes'] as $attr)
        <p>
            <strong>{{ $attr['name'] }}</strong>: 
            Gain = <span style="color: #888;">{{ number_format($attr['gain'], 4) }}</span>, 
            Gain Ratio = <span style="color: #d4a017; font-weight: bold;">{{ number_format($attr['gain_ratio'], 4) }}</span>
        </p>
    @endforeach
</div>
@endif

<div class="card">
    <h2 style="margin-bottom: 1rem;">🌳 Decision Tree (Visual)</h2>
    @if (!empty($tree))
        @include('tree-display', ['tree' => $tree])
    @else
        <p style="color: #666;">Tree belum tersedia.</p>
    @endif
</div>

<div class="card">
    <h2 style="margin-bottom: 1rem;">📋 Decision Tree (JSON)</h2>
    <details>
        <summary style="cursor: pointer; color: #d4a017; margin-bottom: 1rem;">Klik untuk lihat struktur JSON</summary>
        <pre style="background: #0a0a0a; padding: 1rem; overflow-x: auto; border-radius: 4px; border: 1px solid #333; max-height: 400px; overflow-y: auto;">{{ json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </details>
</div>

<a href="{{ route('home') }}" style="display: inline-block; padding: 0.75rem 1.5rem; background: #333; color: #f5f5f5; text-decoration: none; border-radius: 4px; margin-top: 1rem;">
    ← Upload Dataset Lain
</a>
@endsection