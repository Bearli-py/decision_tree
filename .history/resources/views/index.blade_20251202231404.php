@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd;">
    <h1>Upload Dataset CSV</h1>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="margin-bottom: 15px;">
            <label>File CSV:</label><br>
            <input type="file" name="file" accept=".csv" required>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Target Column:</label><br>
            <input type="text" name="target_column" value="Play" placeholder="Contoh: Play" required>
        </div>

        <button type="submit" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer;">
            Proses
        </button>
    </form>
</div>
@endsection
