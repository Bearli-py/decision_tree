<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Analisis - Decision Tree ID3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 50px;
        }

        /* NAVBAR */
        .navbar {
            background: white !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            padding: 15px 0;
        }

        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: #667eea !important;
        }

        .navbar-brand i {
            margin-right: 8px;
        }

        /* HEADER */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .page-header .file-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.95rem;
        }

        /* CONTAINER */
        .results-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* TABS */
        .nav-tabs {
            border: none;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .nav-tabs .nav-link {
            background: white;
            border: none;
            color: #666;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .nav-tabs .nav-link:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        /* STAT CARDS */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-top: 4px solid #667eea;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .stat-card.success {
            border-top-color: #4caf50;
        }

        .stat-card.danger {
            border-top-color: #f44336;
        }

        .stat-card.warning {
            border-top-color: #ff9800;
        }

        .stat-card h6 {
            color: #999;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .stat-card h3 {
            color: #333;
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
        }

        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
            color: #667eea;
        }

        .stat-card.success i {
            color: #4caf50;
        }

        .stat-card.danger i {
            color: #f44336;
        }

        .stat-card.warning i {
            color: #ff9800;
        }

        /* CARD */
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .content-card h3 {
            color: #333;
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 1.5rem;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .content-card h4 {
            color: #667eea;
            font-weight: 700;
            margin: 20px 0 15px;
        }

        /* TABLE */
        .table {
            margin: 0;
        }

        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table thead th {
            border: none;
            font-weight: 600;
            padding: 15px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: #f9f9f9;
        }

        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .badge {
            padding: 8px 12px;
            font-weight: 600;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        /* TREE VISUALIZATION */
        #tree-visualization {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 20px;
            overflow: auto;
        }

        #tree-visualization svg {
            display: block;
            margin: 0 auto;
        }

        /* BUTTON */
        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 30px;
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 30px;
            color: #666;
            margin-top: 60px;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        /* INFO BADGE */
        .info-badge {
            display: inline-block;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }

            .stat-card h3 {
                font-size: 1.8rem;
            }

            .nav-tabs {
                gap: 5px;
            }

            .nav-tabs .nav-link {
                padding: 10px 15px;
                font-size: 0.9rem;
            }

            .content-card {
                padding: 20px;
            }

            #tree-visualization {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-tree"></i> Decision Tree ID3
            </a>
        </div>
    </nav>

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="results-container">
            <h1><i class="fas fa-chart-bar"></i> Hasil Perhitungan Decision Tree</h1>
            <div class="file-badge">
                <i class="fas fa-file-csv"></i> {{ $fileName }}
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="results-container">
        <!-- TABS NAVIGATION -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="statistik-tab" data-bs-toggle="tab" href="#statistik" role="tab">
                    <i class="fas fa-chart-pie"></i> Statistik
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="gain-tab" data-bs-toggle="tab" href="#gain" role="tab">
                    <i class="fas fa-calculator"></i> Information Gain
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tree-tab" data-bs-toggle="tab" href="#tree" role="tab">
                    <i class="fas fa-sitemap"></i> Tree Structure
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="data-tab" data-bs-toggle="tab" href="#data" role="tab">
                    <i class="fas fa-database"></i> Dataset
                </a>
            </li>
        </ul>

        <!-- TAB CONTENT -->
        <div class="tab-content">
            <!-- TAB 1: STATISTIK -->
            <div class="tab-pane fade show active" id="statistik" role="tabpanel">
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <i class="fas fa-list-ol"></i>
                            <h6>Total Data</h6>
                            <h3>{{ $metrics['total_data'] }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card success">
                            <i class="fas fa-check-circle"></i>
                            <h6>Sukses (Yes)</h6>
                            <h3>{{ $metrics['yes_count'] }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card danger">
                            <i class="fas fa-times-circle"></i>
                            <h6>Gagal (No)</h6>
                            <h3>{{ $metrics['no_count'] }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card warning">
                            <i class="fas fa-wave-square"></i>
                            <h6>Entropy Awal</h6>
                            <h3>{{ number_format($metrics['entropy'], 3) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="content-card">
                    <h3>📊 Information Gain per Atribut</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Atribut</th>
                                    <th class="text-end">Gain (IG)</th>
                                    <th class="text-end">Split Info</th>
                                    <th class="text-end">Gain Ratio</th>
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
                                        <td class="text-end"><code>{{ number_format($attr['gain'], 6) }}</code></td>
                                        <td class="text-end"><code>{{ number_format($attr['split_info'], 6) }}</code></td>
                                        <td class="text-end"><code>{{ number_format($attr['gain_ratio'], 6) }}</code></td>
                                        <td>
                                            @if($attr['gain'] == $maxGain)
                                                <span class="info-badge">🌳 ROOT NODE</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: INFORMATION GAIN DETAIL -->
            <div class="tab-pane fade" id="gain" role="tabpanel">
                @foreach($metrics['attributes'] as $attr)
                    <div class="content-card">
                        <h4>{{ $attr['name'] }} <span class="info-badge">Gain: {{ number_format($attr['gain'], 6) }}</span></h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nilai</th>
                                        <th class="text-end">Count</th>
                                        <th class="text-end">Yes</th>
                                        <th class="text-end">No</th>
                                        <th class="text-end">Entropy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attr['values'] as $value)
                                        <tr>
                                            <td><code>{{ $value['value'] }}</code></td>
                                            <td class="text-end">{{ $value['count'] }}</td>
                                            <td class="text-end"><span class="badge badge-success">{{ $value['yes_count'] }}</span></td>
                                            <td class="text-end"><span class="badge badge-danger">{{ $value['no_count'] }}</span></td>
                                            <td class="text-end"><code>{{ number_format($value['entropy'], 6) }}</code></td>
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
                <div class="content-card">
                    <h3>🌳 Struktur Decision Tree (Visual)</h3>
                    <div id="tree-visualization" style="min-height: 600px;"></div>
                </div>
            </div>

            <!-- TAB 4: DATASET -->
            <div class="tab-pane fade" id="data" role="tabpanel">
                <div class="content-card">
                    <h3>📋 Dataset Preview</h3>
                    <div class="table-responsive">
                        <table class="table">
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
                                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Sukses</span>
                                            @else
                                                <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Gagal</span>
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

        <!-- BUTTON -->
        <div class="text-center mb-5">
            <a href="/" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Upload Dataset Lain
            </a>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <p><i class="fas fa-star"></i> Decision Tree ID3 - Algoritma Pembelajaran Mesin untuk Klasifikasi Data</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const treeData = @json($tree);
        const container = document.getElementById('tree-visualization');
        
        if (!treeData || !container) return;
        
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute("width", "1400");
        svg.setAttribute("height", "900");
        svg.setAttribute("style", "border: 1px solid #f0f0f0; background: white; display: block; margin: 0 auto; border-radius: 10px;");
        
        container.appendChild(svg);
        
        const nodeRadius = 45;
        const nodeWidth = 140;
        const nodeHeight = 80;
        const verticalGap = 150;
        let nodePositions = [];
        let nodeId = 0;
        
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
                    line.setAttribute("stroke", "#ddd");
                    line.setAttribute("stroke-width", "2");
                    svg.appendChild(line);
                    
                    const midX = (pos.x + childPos.x) / 2;
                    const midY = (startY + endY) / 2 - 10;
                    
                    const labelBg = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                    labelBg.setAttribute("x", midX - 35);
                    labelBg.setAttribute("y", midY - 12);
                    labelBg.setAttribute("width", "70");
                    labelBg.setAttribute("height", "20");
                    labelBg.setAttribute("fill", "white");
                    svg.appendChild(labelBg);
                    
                    const label = document.createElementNS("http://www.w3.org/2000/svg", "text");
                    label.setAttribute("x", midX);
                    label.setAttribute("y", midY + 3);
                    label.setAttribute("text-anchor", "middle");
                    label.setAttribute("font-size", "12");
                    label.setAttribute("font-weight", "bold");
                    label.setAttribute("fill", "#667eea");
                    label.textContent = child.label
    .replace('? 50', '≥ 50')
    .replace('< 50', '< 50');
                    svg.appendChild(label);
                });
            }
        });
        
        nodePositions.forEach((pos) => {
            if (pos.node.type === 'decision') {
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