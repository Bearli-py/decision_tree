{{-- resources/views/results.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-neutral-950 text-neutral-100 px-4 py-10">
    <div class="max-w-6xl mx-auto space-y-8">
        <header class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-neutral-500 mb-1">
                    Decision Tree Result
                </p>
                <h1 class="text-2xl md:text-3xl font-semibold">
                    Analisis Dataset: {{ $fileName ?? 'Dataset' }}
                </h1>
                <p class="mt-2 text-sm text-neutral-400">
                    Target kolom:
                    <span class="font-medium text-amber-400">
                        {{ $metrics['target'] ?? ($target_column ?? '—') }}
                    </span>
                </p>
            </div>

            <a href="{{ url('/') }}"
               class="inline-flex items-center px-3 py-1.5 text-xs font-medium border border-neutral-700
                      rounded-[4px] text-neutral-300 hover:bg-neutral-900 transition-colors">
                Upload dataset lain
            </a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Panel ringkasan & metrik --}}
            <section class="lg:col-span-1 space-y-4">
                <div class="bg-neutral-900 border border-neutral-800 rounded-[6px] p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-neutral-500 mb-2">
                        Ringkasan dataset
                    </p>
                    <dl class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-neutral-400">Total baris</dt>
                            <dd class="font-medium">{{ $metrics['total_data'] ?? count($data ?? []) }}</dd>
                        </div>
                        @if (!empty($metrics['class_counts']))
                            @foreach ($metrics['class_counts'] as $label => $count)
                                <div class="flex justify-between">
                                    <dt class="text-neutral-400">Kelas “{{ $label }}”</dt>
                                    <dd class="font-medium">{{ $count }}</dd>
                                </div>
                            @endforeach
                        @endif
                        <div class="flex justify-between mt-2 pt-2 border-t border-neutral-800">
                            <dt class="text-neutral-400">Entropy total</dt>
                            <dd class="font-mono text-sm">
                                {{ isset($metrics['entropy']) ? number_format($metrics['entropy'], 4) : '—' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                @if (!empty($metrics['attributes']))
                    <div class="bg-neutral-900 border border-neutral-800 rounded-[6px] p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-neutral-500 mb-3">
                            Atribut &amp; Gain Ratio
                        </p>
                        <div class="space-y-3 text-xs">
                            @foreach ($metrics['attributes'] as $attr)
                                <div class="flex items-center justify-between">
                                    <span class="text-neutral-300">{{ $attr['name'] }}</span>
                                    <span class="font-mono text-amber-400">
                                        GR: {{ number_format($attr['gain_ratio'], 4) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>

            {{-- Panel tree --}}
            <section class="lg:col-span-2">
                <div class="bg-neutral-900 border border-neutral-800 rounded-[6px] p-4 md:p-6">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs uppercase tracking-[0.2em] text-neutral-500">
                            Decision Tree
                        </p>
                        <p class="text-xs text-neutral-500">
                            Node berwarna emas adalah atribut pemisah utama.
                        </p>
                    </div>

                    @if (!empty($tree) && $tree['type'] === 'decision')
                        @include('tree-display', ['tree' => $tree])
                    @elseif (!empty($tree))
                        {{-- Kalau root sudah leaf --}}
                        <div class="text-sm">
                            <span class="text-neutral-400">Seluruh data jatuh ke kelas:</span>
                            <span class="ml-2 font-semibold text-amber-400">{{ $tree['label'] ?? '—' }}</span>
                        </div>
                    @else
                        <p class="text-sm text-neutral-400">
                            Pohon keputusan belum tersedia. Silakan upload dataset terlebih dahulu.
                        </p>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection