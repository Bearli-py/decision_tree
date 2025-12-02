@extends('layouts.app')

@section('title', 'Hasil Perhitungan')

@section('content')
<div class="container-fluid mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-1"><i class="bi bi-bar-chart"></i> Hasil Perhitungan Decision Tree</h1>
            <p class="text-muted">File: <strong>{{ $dataset->file_name }}</strong></p>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="calculation-tab" data-bs-toggle="tab" href="#calculation">
                <i class="bi bi-calculator"></i> Perhitungan Detail
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tree-tab" data-bs-toggle="tab" href="#tree">
                <i class="bi bi-diagram-3"></i> Visualisasi Tree
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="data-tab" data-bs-toggle="tab" href="#data">
                <i class="bi bi-table"></i> Dataset Preview
            </a>
        </li>
    </ul>

    <div class="tab-content">
        {{-- CALCULATION TAB --}}
        <div id="calculation" class="tab-pane fade show active">
            @include('calculation', ['metrics' => $metrics])
        </div>

        {{-- TREE TAB --}}
        <div id="tree" class="tab-pane fade">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Visualisasi Decision Tree (Interactive)</h5>
                </div>
                <div class="card-body">
                    <div id="tree-visualization" style="min-height: 600px; overflow-x: auto;"></div>
                </div>
            </div>
        </div>

        {{-- DATA TAB --}}
        <div id="data" class="tab-pane fade">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Dataset Preview</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peserta</th>
                                <th>Budget</th>
                                <th>Speaker</th>
                                <th>Topik</th>
                                <th>Play</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                                <tr>
                                    <td>{{ $row['No'] }}</td>
                                    <td>{{ $row['Peserta'] }}</td>
                                    <td>{{ $row['Budget'] }}</td>
                                    <td>{{ $row['Speaker'] }}</td>
                                    <td>{{ $row['Topik'] }}</td>
                                    <td>
                                        @if($row['Play'] === 'yes')
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="mt-4">
        <a href="{{ route('index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Upload
        </a>
    </div>
</div>

{{-- Load D3.js --}}
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="{{ asset('js/tree-visualization.js') }}"></script>

<script>
    // Get tree data dari API
    const datasetId = {{ $dataset->id }};
    const treeDataUrl = "{{ route('api.tree', ['dataset' => $dataset->id]) }}";

    // Load dan visualisasi tree
    fetch(treeDataUrl)
        .then(response => response.json())
        .then(treeData => {
            visualizeTree(treeData, '#tree-visualization');
        });
</script>
@endsection