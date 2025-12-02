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
    <h3>Struktur Decision Tree (Visual)</h3>
    <div id="tree-visualization" style="overflow-x: auto; padding: 20px; background: #f5f5f5; border-radius: 8px; min-height: 600px;"></div>
</div>

<style>
    .tree-node {
        fill: #fff;
        stroke: #333;
        stroke-width: 2;
    }
    
    .tree-node-decision {
        fill: #fff3e0;
        stroke: #f57c00;
        stroke-width: 2;
    }
    
    .tree-node-yes {
        fill: #c8e6c9;
        stroke: #388e3c;
        stroke-width: 2;
    }
    
    .tree-node-no {
        fill: #ffcdd2;
        stroke: #d32f2f;
        stroke-width: 2;
    }
    
    .tree-text {
        font-family: Arial, sans-serif;
        font-size: 13px;
        text-anchor: middle;
    }
    
    .tree-text-bold {
        font-weight: bold;
        font-size: 14px;
    }
    
    .tree-text-small {
        font-size: 11px;
        fill: #666;
    }
    
    .tree-line {
        stroke: #999;
        stroke-width: 2;
        fill: none;
    }
</style>

<script>
    // Data tree dari PHP
    const treeData = @json($tree);
    
    // Render tree visualization
    function renderTreeVisualization(data) {
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute("width", "1200");
        svg.setAttribute("height", "800");
        svg.setAttribute("style", "border: 1px solid #ddd; background: white;");
        
        const container = document.getElementById('tree-visualization');
        container.appendChild(svg);
        
        let nodeId = 0;
        const positions = new Map();
        
        function calculatePositions(node, x, y, offset) {
            const id = nodeId++;
            positions.set(id, { x, y, node });
            
            if (node.type === 'decision') {
                const children = Object.entries(node.children);
                const childCount = children.length;
                const childOffset = offset / 2;
                
                children.forEach((child, index) => {
                    const childX = x + (index - (childCount - 1) / 2) * (offset);
                    const childY = y + 120;
                    calculatePositions(child[1], childX, childY, childOffset);
                });
            }
            
            return id;
        }
        
        calculatePositions(data, 600, 50, 200);
        
        // Draw lines
        positions.forEach((pos, id) => {
            if (pos.node.type === 'decision') {
                Object.values(pos.node.children).forEach((child) => {
                    // Find child position
                    let childPos = null;
                    positions.forEach((p, childId) => {
                        if (p.node === child) childPos = p;
                    });
                    
                    if (childPos) {
                        const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
                        line.setAttribute("x1", pos.x);
                        line.setAttribute("y1", pos.y + 50);
                        line.setAttribute("x2", childPos.x);
                        line.setAttribute("y2", childPos.y - 50);
                        line.setAttribute("class", "tree-line");
                        svg.appendChild(line);
                    }
                });
            }
        });
        
        // Draw nodes
        positions.forEach((pos) => {
            const x = pos.x;
            const y = pos.y;
            const node = pos.node;
            
            if (node.type === 'decision') {
                // Decision node - rounded rectangle
                const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                rect.setAttribute("x", x - 70);
                rect.setAttribute("y", y - 40);
                rect.setAttribute("width", "140");
                rect.setAttribute("height", "80");
                rect.setAttribute("rx", "8");
                rect.setAttribute("class", "tree-node-decision");
                svg.appendChild(rect);
                
                const text1 = document.createElementNS("http://www.w3.org/2000/svg", "text");
                text1.setAttribute("x", x);
                text1.setAttribute("y", y - 15);
                text1.setAttribute("class", "tree-text tree-text-bold");
                text1.textContent = node.attribute;
                svg.appendChild(text1);
                
                const text2 = document.createElementNS("http://www.w3.org/2000/svg", "text");
                text2.setAttribute("x", x);
                text2.setAttribute("y", y + 10);
                text2.setAttribute("class", "tree-text tree-text-small");
                text2.textContent = "Gain: " + node.gain.toFixed(4);
                svg.appendChild(text2);
                
            } else {
                // Leaf node - circle
                const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                circle.setAttribute("cx", x);
                circle.setAttribute("cy", y);
                circle.setAttribute("r", "45");
                
                if (node.label === 'Yes') {
                    circle.setAttribute("class", "tree-node-yes");
                    const text1 = document.createElementNS("http://www.w3.org/2000/svg", "text");
                    text1.setAttribute("x", x);
                    text1.setAttribute("y", y - 10);
                    text1.setAttribute("class", "tree-text tree-text-bold");
                    text1.style.fill = "#388e3c";
                    text1.textContent = "✓ SUKSES";
                    svg.appendChild(text1);
                } else {
                    circle.setAttribute("class", "tree-node-no");
                    const text1 = document.createElementNS("http://www.w3.org/2000/svg", "text");
                    text1.setAttribute("x", x);
                    text1.setAttribute("y", y - 10);
                    text1.setAttribute("class", "tree-text tree-text-bold");
                    text1.style.fill = "#d32f2f";
                    text1.textContent = "✗ GAGAL";
                    svg.appendChild(text1);
                }
                
                svg.appendChild(circle);
                
                const text2 = document.createElementNS("http://www.w3.org/2000/svg", "text");
                text2.setAttribute("x", x);
                text2.setAttribute("y", y + 15);
                text2.setAttribute("class", "tree-text tree-text-small");
                text2.textContent = "(" + node.count + " data)";
                svg.appendChild(text2);
            }
        });
    }
    
    // Render saat page load
    document.addEventListener('DOMContentLoaded', function() {
        renderTreeVisualization(treeData);
    });
</script>

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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const treeData = @json($tree);
    
    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("width", "1400");
    svg.setAttribute("height", "900");
    svg.setAttribute("style", "border: 1px solid #ddd; background: white;");
    
    const container = document.getElementById('tree-visualization');
    if (container) {
        container.appendChild(svg);
    }
    
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
    
    // Draw lines
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
                
                // Label
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
                label.setAttribute("fill", "#d32f2f");
                label.textContent = child.label;
                svg.appendChild(label);
            });
        }
    });
    
    // Draw nodes
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
</html>