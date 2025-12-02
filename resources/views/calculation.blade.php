<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-calculator"></i> Ringkasan Dataset</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <h6>Total Data</h6>
                <h3 class="text-primary">{{ $metrics['total_data'] }}</h3>
            </div>
            <div class="col-md-3 text-center">
                <h6>Yes (Sukses)</h6>
                <h3 class="text-success">{{ $metrics['yes_count'] }}</h3>
            </div>
            <div class="col-md-3 text-center">
                <h6>No (Gagal)</h6>
                <h3 class="text-danger">{{ $metrics['no_count'] }}</h3>
            </div>
            <div class="col-md-3 text-center">
                <h6>Entropy Awal</h6>
                <h3 class="text-warning">{{ number_format($metrics['entropy'], 6) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Perhitungan Information Gain & Gain Ratio</h5>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Atribut</th>
                    <th>Gain (IG)</th>
                    <th>Split Info</th>
                    <th>Gain Ratio</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($metrics['attributes'] as $attr)
                    <tr>
                        <td><strong>{{ $attr['name'] }}</strong></td>
                        <td><code>{{ number_format($attr['gain'], 6) }}</code></td>
                        <td><code>{{ number_format($attr['split_info'], 6) }}</code></td>
                        <td><code>{{ number_format($attr['gain_ratio'], 6) }}</code></td>
                        <td>
                            @php
                                $maxGain = max(array_column($metrics['attributes'], 'gain'));
                            @endphp
                            @if($attr['gain'] == $maxGain)
                                <span class="badge bg-success">ROOT NODE</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-diagram-2"></i> Detail Per Atribut</h5>
    </div>
    <div class="card-body">
        @foreach($metrics['attributes'] as $attr)
            <div class="mb-4">
                <h6 class="border-bottom pb-2">
                    <i class="bi bi-tag"></i> {{ strtoupper($attr['name']) }}
                    <span class="float-end badge bg-primary">Gain: {{ number_format($attr['gain'], 6) }}</span>
                </h6>

                <div class="table-responsive">
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
</div>