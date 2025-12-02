@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 50px auto; padding: 20px;">
    <h1>Hasil Decision Tree</h1>
    
    <p><strong>File:</strong> {{ $fileName ?? 'Unknown' }}</p>
    <p><strong>Target:</strong> {{ $metrics['target'] ?? '—' }}</p>
    <p><strong>Total Data:</strong> {{ $metrics['total_data'] ?? 0 }}</p>
    <p><strong>Entropy:</strong> {{ number_format($metrics['entropy'] ?? 0, 4) }}</p>

    <hr>

    <h2>Atribut & Gain Ratio</h2>
    @if(!empty($metrics['attributes']))
        <ul>
            @foreach($metrics['attributes'] as $attr)
                <li>
                    <strong>{{ $attr['name'] }}</strong>: 
                    Gain = {{ number_format($attr['gain'], 4) }}, 
                    Gain Ratio = {{ number_format($attr['gain_ratio'], 4) }}
                </li>
            @endforeach
        </ul>
    @else
        <p>Tidak ada atribut tersedia.</p>
    @endif

    <hr>

    <h2>Decision Tree</h2>
    <pre style="background: #f4f4f4; padding: 15px; overflow-x: auto;">{{ json_encode($tree, JSON_PRETTY_PRINT) }}</pre>

    <a href="{{ route('home') }}" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #2196F3; color: white; text-decoration: none;">
        Upload Dataset Lain
    </a>
</div>
@endsection
