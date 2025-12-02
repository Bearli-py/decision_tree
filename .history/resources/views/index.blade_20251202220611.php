{{-- resources/views/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-neutral-950 text-neutral-100 flex items-center justify-center px-4">
    <div class="max-w-3xl w-full">
        <header class="mb-8">
            <p class="text-sm uppercase tracking-[0.3em] text-neutral-500 mb-2">
                Decision Intelligence
            </p>
            <h1 class="text-3xl md:text-4xl font-semibold tracking-tight">
                Event Decision Tree Dashboard
            </h1>
            <p class="mt-3 text-sm text-neutral-400 max-w-xl">
                Unggah dataset dalam format CSV dan bangun pohon keputusan secara otomatis
                untuk membantu tim membuat keputusan yang terukur dan konsisten.
            </p>
        </header>

        <div class="bg-neutral-900 border border-neutral-800 rounded-[6px] p-6 md:p-8 flex flex-col md:flex-row gap-8">
            <div class="flex-1">
                @if ($errors->any())
                    <div class="mb-4 border border-red-800 bg-red-950/40 text-red-200 px-4 py-3 text-sm rounded-[4px]">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/upload') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-[0.2em] text-neutral-400 mb-2">
                            Dataset (CSV)
                        </label>
                        <div
                            class="relative flex items-center justify-between gap-3 border border-dashed border-neutral-700 bg-neutral-900/40 px-4 py-3 rounded-[4px] hover:border-amber-500/80 transition-colors">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium">Pilih file CSV</span>
                                <span class="text-xs text-neutral-500">
                                    Struktur bebas, minimal memiliki header kolom.
                                </span>
                            </div>
                            <label
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-amber-500 text-neutral-950 rounded-[4px] cursor-pointer hover:bg-amber-400 transition-colors">
                                Browse
                                <input type="file" name="file" class="hidden" accept=".csv,text/csv">
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-[0.2em] text-neutral-400 mb-2">
                            Target column
                        </label>
                        <input
                            type="text"
                            name="target_column"
                            placeholder="Contoh: Play"
                            value="{{ old('target_column', 'Play') }}"
                            class="w-full bg-neutral-900 border border-neutral-700 rounded-[4px] px-3 py-2 text-sm
                                   focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500
                                   placeholder:text-neutral-600">
                        <p class="mt-1 text-xs text-neutral-500">
                            Nama kolom yang menjadi target klasifikasi di dataset kamu.
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <p class="text-xs text-neutral-500">
                            File hanya digunakan sementara untuk perhitungan &amp; visualisasi.
                        </p>
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-amber-500 text-neutral-950
                                   rounded-[4px] hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500/60
                                   focus:ring-offset-2 focus:ring-offset-neutral-950 transition-colors">
                            Bangun Decision Tree
                        </button>
                    </div>
                </form>
            </div>

            <aside class="w-full md:w-64 border border-neutral-800 rounded-[6px] bg-neutral-950/60 p-4 space-y-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-neutral-500 mb-1">Insight preview</p>
                    <p class="text-sm text-neutral-300">
                        Sistem akan menghitung entropy, information gain, dan gain ratio untuk seluruh atribut.
                    </p>
                </div>
                <div class="border-t border-neutral-800 pt-3">
                    <p class="text-xs text-neutral-500 mb-1">Rekomendasi</p>
                    <ul class="space-y-1 text-xs text-neutral-400">
                        <li>• Gunakan header kolom yang jelas.</li>
                        <li>• Pastikan kolom target berisi nilai kategorikal.</li>
                        <li>• Hindari data kosong yang berlebihan.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection