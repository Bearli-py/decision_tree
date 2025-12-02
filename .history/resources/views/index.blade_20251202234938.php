@extends('layouts.app')

@section('content')
<div class="card">
    <h1 style="font-size: 2rem; margin-bottom: 1rem;">Upload Dataset CSV</h1>
    
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <label>File CSV:</label>
        <input type="file" name="file" accept=".csv" required>

        <label>Target Column:</label>
        <input type="text" name="target_column" value="Play" required>

        <button type="submit">🚀 Bangun Decision Tree</button>
    </form>
</div>
@endsection