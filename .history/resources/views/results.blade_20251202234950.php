@extends('layouts.app')

@section('content')
<div class="card">
    <h1 style="margin-bottom: 1rem;">Hasil: {{ $fileName ?? 'Dataset' }}</h1>
    <p>Target: <strong>{{ $metrics['target'] ?? '?' }}</strong></p>
    <p>Total Data: <strong>{{ $metrics['total_data'] ?? 0 }}</strong></p>
    <p>Entropy: <strong>{{ number_format($metrics['entropy'] ?? 0, 4) }}</strong></p>
</div>

@if(!empty($metrics['attributes']))
<div class="card">
    <h2 style="margin-bottom: 1rem;">Atribut & Gain Ratio</h2>
    @foreach($metrics['attributes'] as $attr)
        <p>{{ $attr['name'] }}: <strong style="color: #d4a017;">{{ number_format($attr['gain_ratio'], 4) }}</strong></p>
    @endforeach
</div>
@endif

<div class="card">
    <h2 style="margin-bottom: 1rem;">Decision Tree</h2>
    <pre style="background: #0a0a0a; padding: 1rem; overflow-x: auto; border-radius: 4px;">{{ json_encode($tree, JSON_PRETTY_PRINT) }}</pre>
</div>

<a href="{{ route('home') }}" style="display: inline-block; padding: 0.75rem 1.5rem; background: #333; color: #f5f5f5; text-decoration: none; border-radius: 4px;">← Upload Lagi</a>
@endsection