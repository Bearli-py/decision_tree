@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">📊 Hasil Analisis</h1>
            <p style="color: #718096;">
                <strong>File:</strong> {{ $fileName ?? 'Dataset' }} &nbsp;|&nbsp;
                <strong>Target:</strong> <span class="badge badge-purple">{{ $metrics['target'] ?? '?' }}</span>
            </p>
        </div>
        <a href="{{ route('home') }}" style="padding: 0.7rem 1.2rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-decoration: none; border-radius: 10px; font-weight: 600; box-shadow: 0 4px 12px rgba(102,126,234,0.3);">
            ← Upload Lagi
        </a>
    </div>
</div>

{{-- Ringkasan Dataset --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    <div class="card" style="text-align: center; background: linear-gradient(135deg, #fbbf24, #f59e0b);">
        <div style="font-size: 2.5rem; font-weight: 800; color: white;">{{ $metrics['total_data'] ?? 0 }}</div>
        <div style="color: rgba(255,255,255,0.9); font-weight: 600; margin-top: 0.5rem;">Total Data</div>
    </div>
    
    @if (!empty($metrics['class_counts']))
        @foreach ($metrics['class_counts'] as $label => $count)
            <div class="card" style="text-align: center; background: linear-gradient(135deg, #a78bfa, #8b5cf6);">
                <div style="font-size: 2.5rem; font-weight: 800; color: white;">{{ $count }}</div>
                <div style="color: rgba(255,255,255,0.9); font-weight: 600; margin-top: 0.5rem;">Kelas "{{ $label }}"</div>
            </div>
        @endforeach
    @endif
    
    <div class="card" style="text-align: center; background: linear-gradient(135deg, #34d399, #10b981);">
        <div style="font-size: 2rem; font-weight: 800; color: white; font-family: monospace;">{{ number_format($metrics['entropy'] ?? 0, 4) }}</div>
        <div style="color: rgba(255,255,255,0.9); font-weight: 600; margin-top: 0.5rem;">Entropy Total</div>
    </div>
</div>

{{-- Detail Perhitungan Atribut --}}
@if (!empty($metrics['attributes']))
<div class="card">
    <h2 style="font-size: 1.6rem; margin-bottom: 1.5rem;">📐 Detail Perhitungan Atribut</h2>
    
    @foreach ($metrics['attributes'] as $attr)
        <div style="background: linear-gradient(135deg, #f3f4f6, #e5e7eb); padding: 1.5rem; border-radius: 12px; margin-bottom: 1rem; border-left: 5px solid #667eea;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="color: #667eea; font-size: 1.3rem; margin: 0;">{{ $attr['name'] }}</h3>
                <div>
                    <span class="badge badge-amber">Gain: {{ number_format($attr['gain'], 4) }}</span>
                    <span class="badge badge-green">Gain Ratio: {{ number_format($attr['gain_ratio'], 4) }}</span>
                    <span class="badge badge-purple">Split Info: {{ number_format($attr['split_info'], 4) }}</span>
                </div>
            </div>
            
            {{-- Tabel nilai atribut --}}
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                        <th style="padding: 0.8rem; text-align: left; font-weight: 600;">Nilai</th>
                        <th style="padding: 0.8rem; text-align: center; font-weight: 600;">Total</th>
                        <th style="padding: 0.8rem; text-align: left; font-weight: 600;">Distribusi Kelas</th>
                        <th style="padding: 0.8rem; text-align: center; font-weight: 600;">Entropy</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attr['values'] as $val)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 0.8rem; font-weight: 600; color: #4a5568;">{{ $val['value'] }}</td>
                            <td style="padding: 0.8rem; text-align: center; font-weight: 700; color: #667eea;">{{ $val['count'] }}</td>
                            <td style="padding: 0.8rem;">
                                @foreach ($val['class_counts'] as $cls => $cnt)
                                    <span class="badge badge-purple">{{ $cls }}: {{ $cnt }}</span>
                                @endforeach
                            </td>
                            <td style="padding: 0.8rem; text-align: center; font-family: monospace; font-weight: 600; color: #10b981;">
                                {{ number_format($val['entropy'], 4) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>
@endif

{{-- Decision Tree Visual --}}
<div class="card">
    <h2 style="font-size: 1.6rem; margin-bottom: 1.5rem;">🌳 Decision Tree (Visual)</h2>
    <p style="color: #718096; margin-bottom: 1.5rem; font-size: 0.9rem;">
        Pohon keputusan dibangun berdasarkan atribut dengan <strong>Information Gain tertinggi</strong> pada setiap level.
    </p>
    
    @if (!empty($tree))
        @include('tree-visual', ['tree' => $tree])
    @else
        <p style="color: #a0aec0;">Tree belum tersedia.</p>
    @endif
</div>

@endsection