<!DOCTYPE html>
<html>
<head>
    <title>Hasil Perhitungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .tree-container {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            font-family: monospace;
            overflow-x: auto;
        }
        .tree-node {
            display: inline-block;
            padding: 8px 12px;
            margin: 5px;
            border-radius: 4px;
            background: #e3f2fd;
            border: 1px solid #1976d2;
        }
        .tree-leaf {
            background: #c8e6c9;
            border: 1px solid #388e3c;
        }
        .tree-decision {
            background: #fff3e0;
            border: 1px solid #f57c00;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Hasil Perhitungan Decision Tree</h1>
        <p>File: <strong>{{ $fileName }}</strong></p>

        <hr>

        <!-- TABS -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="#stats" data-bs-toggle="tab">Statistik</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#gain" data-bs-toggle="tab">Information Gain</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tree" data-bs-toggle="tab">Tree Structure</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#data" data-bs-toggle="tab">Dataset</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- TAB 1: STATISTIK -->
            <div class="tab-pane fade show active" id="stats">
                <h3>Statistik Dataset</h3>
                <table class="table">
                    <tr>
                        <td><strong>Total Data:</strong></td>
                        <td>{{ $metrics['total_data'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Yes (Sukses):</strong></td>
                        <td>{{ $metrics['yes_count'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>No (Gagal):</strong></td>
                        <td>{{ $metrics['no_count'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Entropy Awal:</strong></td>
                        <td><code>{{ number_format($metrics['entropy'], 6) }}</code></td>
                    </tr>
                </table>

                <hr>

                <h3>Information Gain & Gain Ratio per Atribut</h3>
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Atribut</th>
                            <th>Gain (IG)</th>
                            <th>Split Info</th>
                            <th>Gain Ratio</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $maxGain = max(array_column($metrics['attributes'], 'gain'));
                        @endphp
                        @foreach($metrics['attributes'] as $attr)
                            <tr>
                                <td><strong>{{ $attr['name'] }}</strong></td>
                                <td><code>{{ number_format($attr['gain'], 6) }}</code></td>
                                <td><code>{{ number_format($attr['split_info'], 6) }}</code></td>
                                <td><code>{{ number_format($attr['gain_ratio'], 6) }}</code></td>
                                <td>
                                    @if($attr['gain'] == $maxGain)
                                        <span class="badge bg-success">ROOT NODE</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TAB 2: GAIN DETAILS -->
            <div class="tab-pane fade" id="gain">
                <h3>Detail per Atribut</h3>
                @foreach($metrics['attributes'] as $attr)
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>{{ $attr['name'] }}</strong> - Gain: {{ number_format($attr['gain'], 6) }}
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Value</th>
                                        <th>Count</th>
                                        <th>Yes</th>
                                        <th>No</th>
                                        <th>Entropy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attr['values'] as $value)
                                        <tr>
                                            <td><code>{{ $value['value'] }}</code></td>
                                            <td>{{ $value['count'] }}</td>
                                            <td><span class="badge bg-success">{{ $value['yes_count'] }}</span></td>
                                            <td><span class="badge bg-danger">{{ $value['no_count'] }}</span></td>
                                            <td><code>{{ number_format($value['entropy'], 6) }}</code></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- TAB 3: TREE STRUCTURE -->
            <div class="tab-pane fade" id="tree">
    <h3>Struktur Decision Tree</h3>
    @include('tree-display-vertical', ['tree' => $tree])
</div>

            <!-- TAB 4: DATASET -->
            <div class="tab-pane fade" id="data">
                <h3>Dataset Preview</h3>
                <table class="table table-striped table-sm">
                    <thead class="table-dark">
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

        <hr>
        <a href="/" class="btn btn-secondary">Upload Dataset Lain</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>