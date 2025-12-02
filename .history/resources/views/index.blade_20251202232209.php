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

        <div class="bg-neutral-900 border border-neutral-800 rounded-md p-6 md:p-8">
            @if ($errors->any())
                <div class="mb-4 border border-red-800 bg-red-950/40 text-red-200 px-4 py-3 text-sm rounded-md">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-medium uppercase tracking-[0.2em] text-neutral-400 mb-2">
                        Dataset (CSV)
                    </label>
                    <div class="relative flex items-center justify-between gap-3 border border-dashed border-neutral-700 bg-neutral-900/40 px-4 py-3 rounded-md hover:border-amber-500/80 transition-colors">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium">Pilih file CSV</span>
                            <span class="text-xs text-neutral-500">
                                Struktur bebas, minimal memiliki header kolom.
                            </span>
                        </div>
                        <label class="inline-flex items-center px-3 py-1.5 text-xs font-medium bg-amber-500 text-neutral-950 rounded-md cursor-pointer hover:bg-amber-400 transition-colors">
                            Browse
                            <input type="file" name="file" class="hidden" accept=".csv,text/csv" required>
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
                        required
                        class="w-full bg-neutral-900 border border-neutral-700 rounded-md px-3 py-2 text-sm
                               focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500
                               placeholder:text-neutral-600">
                    <p class="mt-1 text-xs text-neutral-500">
                        Nama kolom yang menjadi target klasifikasi di dataset kamu.
                    </p>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <p class="text-xs text-neutral-500">
                        File hanya digunakan sementara untuk perhitungan.
                    </p>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-amber-500 text-neutral-950
                               rounded-md hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500/60
                               focus:ring-offset-2 focus:ring-offset-neutral-950 transition-colors">
                        Bangun Decision Tree
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection