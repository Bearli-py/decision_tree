<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Decision Tree Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-neutral-950 text-neutral-100">
    
    {{-- Navbar modern dengan Tailwind --}}
    <nav class="border-b border-neutral-800 bg-neutral-900/80 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight">
                        Decision Tree
                    </a>
                </div>
                
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" 
                       class="text-sm text-neutral-400 hover:text-neutral-100 transition-colors">
                        Home
                    </a>
                    <a href="{{ route('home') }}" 
                       class="text-sm text-neutral-400 hover:text-neutral-100 transition-colors">
                        Upload
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Konten halaman --}}
    <main>
        @yield('content')
    </main>

</body>
</html>