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
    <h2 style="margin-bottom: 1.5rem;">🌳 Decision Tree (Visual)</h2>
    @if (!empty($tree))
        @include('tree-visual', ['tree' => $tree])
    @else
        <p style="color: #666;">Tree belum tersedia.</p>
    @endif
</div>

<a href="{{ route('home') }}" style="display: inline-block; padding: 0.75rem 1.5rem; background: #333; color: #f5f5f5; text-decoration: none; border-radius: 4px; margin-top: 1rem;">
    ← Upload Dataset Lain
</a>
@endsection