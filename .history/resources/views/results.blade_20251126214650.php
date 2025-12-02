<!DOCTYPE html>
<html>
<head>
    <title>Hasil Perhitungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
        }
        .container-lg {
            background: white;
            border-radius: 8px;
            padding: 30px;
            margin-top: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .nav-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 3px solid transparent;
        }
        .nav-tabs .nav-link.active {
            color: #0066cc;
            border-bottom: 3px solid #0066cc;
            background: none;
        }
        .table-dark {
            background: #2c3e50;
        }
        .badge {
            padding: 6px 10px;
        }
        #tree-visualization {
            overflow: auto;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container-lg">
        <h1 class="mb-3">
            <i class="bi bi-bar-chart"></i> Hasil Perhitungan Decision Tree
        </h1>
        <p class="text-muted">File: <strong>{{ $fileName }}</strong></p>

        <hr>

        <!-- TABS NAVIGATION -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="statistik-tab" data-bs-toggle="tab" href="#statistik" role="tab">
                    Statistik
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="gain-tab" data-bs-toggle="tab" href="#gain" role="tab">
                    Information Gain
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tree-tab" data-bs-toggle="tab" href="#tree" role="tab">
                    Tree Structure
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="data-tab" data-bs-toggle="tab" href="#data" role="tab">
                    Dataset
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- TAB 1: STATISTIK -->
            <div class="tab-pane fade show active" id="statistik" role="tabpanel">
                <h3 class="mb-3">Statistik Dataset</h3>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h6 class="card-title">Total Data</h6>
                                <h3 class="text-primary">{{ $metrics['total_data'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h6 class="card-title">Yes (Sukses)</h6>
                                <h3 class="text-success">{{ $metrics['yes_count'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h6 class="card-title">No (Gagal)</h6>
                                <h3 class="text-danger">{{ $metrics['no_count'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h6 class="card-title">Entropy Awal</h6>
                                <h3 class="text-warning">{{ number_format($metrics['entropy'], 6) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <h4 class="mt-4 mb-3">Information Gain per Atribut</h4>
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

            <!-- TAB 2: INFORMATION GAIN DETAIL -->
            <div class="tab-pane fade" id="gain" role="tabpanel">
                <h3 class="mb-3">Detail Information Gain per Atribut</h3>
                @foreach($metrics['attributes'] as $attr)
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <strong>{{ $attr['name'] }}</strong>
                            <span class="float-end">Gain: {{ number_format($attr['gain'], 6) }}</span>
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
            <div class="tab-pane fade" id="tree" role="tabpanel">
                <h3 class="mb-3">Struktur Decision Tree (Visual)</h3>
                <div id="tree-visualization" style="min-height: 600px;"></div>
            </div>

            <!-- TAB 4: DATASET -->
            <div class="tab-pane fade" id="data" role="tabpanel">
                <h3 class="mb-3">Dataset Preview</h3>
                <div class="table-responsive">
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
        </div>

        <hr class="mt-5">
        <a href="/" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Upload Dataset Lain
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const treeData = @json($tree);
        const container = document.getElementById('tree-visualization');
        
        if (!treeData || !container) return;
        
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute("width", "1400");
        svg.setAttribute("height", "900");
        svg.setAttribute("style", "border: 1px solid #ddd; background: white; display: block; margin: 0 auto;");
        
        container.appendChild(svg);
        
        const nodeRadius = 45;
        const nodeWidth = 140;
        const nodeHeight = 80;
        const verticalGap = 150;
        let nodePositions = [];
        let nodeId = 0;
        
        // Layout tree
        function layoutTree(node, x, y, offset) {
            const id = nodeId++;
            nodePositions[id] = {
                id: id,
                x: x,
                y: y,
                node: node,
                children: []
            };
            
            if (node.type === 'decision') {
                const childEntries = Object.entries(node.children);
                const childCount = childEntries.length;
                const totalWidth = (childCount - 1) * offset;
                const startX = x - totalWidth / 2;
                
                childEntries.forEach((entry, index) => {
                    const childX = startX + index * offset;
                    const childY = y + verticalGap;
                    const childId = layoutTree(entry[1], childX, childY, offset / 1.5);
                    nodePositions[id].children.push({
                        label: entry[0],
                        childId: childId
                    });
                });
            }
            
            return id;
        }
        
        layoutTree(treeData, 700, 50, 300);
        
        // Draw lines and labels
        nodePositions.forEach((pos) => {
            if (pos.node.type === 'decision') {
                pos.children.forEach((child) => {
                    const childPos = nodePositions[child.childId];
                    
                    const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
                    let startY = pos.y + nodeHeight / 2;
                    let endY = childPos.y - (childPos.node.type === 'decision' ? nodeHeight / 2 : nodeRadius);
                    
                    line.setAttribute("x1", pos.x);
                    line.setAttribute("y1", startY);
                    line.setAttribute("x2", childPos.x);
                    line.setAttribute("y2", endY);
                    line.setAttribute("stroke", "#999");
                    line.setAttribute("stroke-width", "2");
                    svg.appendChild(line);
                    
                    // Label on line
                    const midX = (pos.x + childPos.x) / 2;
                    const midY = (startY + endY) / 2 - 10;
                    
                    const labelBg = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                    labelBg.setAttribute("x", midX - 35);
                    labelBg.setAttribute("y", midY - 12);
                    labelBg.setAttribute("width", "70");
                    labelBg.setAttribute("height", "20");
                    labelBg.setAttribute("fill", "white");
                    labelBg.setAttribute("stroke", "none");
                    svg.appendChild(labelBg);
                    
                    const label = document.createElementNS("http://www.w3.org/2000/svg", "text");
                    label.setAttribute("x", midX);
                    label.setAttribute("y", midY + 3);
                    label.setAttribute("text-anchor", "middle");
                    label.setAttribute("font-size", "12");
                    label.setAttribute("font-weight", "bold");
                    label.setAttribute("fill", "#d32f2f");
                    label.textContent = child.label;
                    svg.appendChild(label);
                });
            }
        });
        
        // Draw nodes
        nodePositions.forEach((pos) => {
            if (pos.node.type === 'decision') {
                // Decision node
                const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                rect.setAttribute("x", pos.x - nodeWidth / 2);
                rect.setAttribute("y", pos.y - nodeHeight / 2);
                rect.setAttribute("width", nodeWidth);
                rect.setAttribute("height", nodeHeight);
                rect.setAttribute("rx", "8");
                rect.setAttribute("fill", "#fff3e0");
                rect.setAttribute("stroke", "#f57c00");
                rect.setAttribute("stroke-width", "2");
                svg.appendChild(rect);
                
                const text1 = document.createElementNS("http://www.w3.org/2000/svg", "text");
                text1.setAttribute("x", pos.x);
                text1.setAttribute("y", pos.y - 15);
                text1.setAttribute("text-anchor", "middle");
                text1.setAttribute("font-size", "14");
                text1.setAttribute("font-weight", "bold");
                text1.setAttribute("fill", "#d32f2f");
                text1.textContent = pos.node.attribute;
                svg.appendChild(text1);
                
                const text2 = document.createElementNS("http://www.w3.org/2000/svg", "text");
                text2.setAttribute("x", pos.x);
                text2.setAttribute("y", pos.y + 10);
                text2.setAttribute("text-anchor", "middle");
                text2.setAttribute("font-size", "11");
                text2.setAttribute("fill", "#666");
                text2.textContent = "Gain: " + pos.node.gain.toFixed(4);
                svg.appendChild(text2);
                
            } else {
                // Leaf node
                const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                circle.setAttribute("cx", pos.x);
                circle.setAttribute("cy", pos.y);
                circle.setAttribute("r", nodeRadius);
                circle.setAttribute("fill", pos.node.label === 'Yes' ? "#c8e6c9" : "#ffcdd2");
                circle.setAttribute("stroke", pos.node.label === 'Yes' ? "#388e3c" : "#d32f2f");
                circle.setAttribute("stroke-width", "2");
                svg.appendChild(circle);
                
                const text1 = document.createElementNS("http://www.w3.org/2000/svg", "text");
                text1.setAttribute("x", pos.x);
                text1.setAttribute("y", pos.y - 5);
                text1.setAttribute("text-anchor", "middle");
                text1.setAttribute("font-size", "14");
                text1.setAttribute("font-weight", "bold");
                text1.setAttribute("fill", pos.node.label === 'Yes' ? "#388e3c" : "#d32f2f");
                text1.textContent = pos.node.label === 'Yes' ? "✓ SUKSES" : "✗ GAGAL";
                svg.appendChild(text1);
                
                const text2 = document.createElementNS("http://www.w3.org/2000/svg", "text");
                text2.setAttribute("x", pos.x);
                text2.setAttribute("y", pos.y + 15);
                text2.setAttribute("text-anchor", "middle");
                text2.setAttribute("font-size", "11");
                text2.setAttribute("fill", "#666");
                text2.textContent = "(" + pos.node.count + " data)";
                svg.appendChild(text2);
            }
        });
    });
    </script>
</body>
</html>