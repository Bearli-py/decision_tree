<style>
    .tree-wrapper {
        padding: 40px;
        background: linear-gradient(135deg, #fafafa, #f3f4f6);
        border-radius: 16px;
        overflow-x: auto;
        border: 2px solid #e5e7eb;
        position: relative;
    }
    
    .node-box {
        display: inline-block;
        padding: 18px 30px;
        margin: 15px;
        border-radius: 14px;
        font-weight: 700;
        text-align: center;
        min-width: 150px;
        position: relative;
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        transition: transform 0.2s ease;
    }
    
    .node-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.2);
    }
    
    .decision-box {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #78350f;
        border: 3px solid #d97706;
    }
    
    .leaf-yes {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        color: #064e3b;
        border: 3px solid #059669;
    }
    
    .leaf-no {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        color: #7f1d1d;
        border: 3px solid #dc2626;
    }
    
    .node-content { font-size: 15px; font-weight: 800; letter-spacing: 0.3px; }
    .node-info { font-size: 11px; margin-top: 6px; opacity: 0.85; font-weight: 500; }
    
    .tree-row {
        display: flex;
        justify-content: center;
        margin: 40px 0;
        position: relative;
    }
    
    .tree-column {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0 20px;
        position: relative;
    }
    
    .edge-label {
        display: inline-block;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        border: 2px solid #818cf8;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 13px;
        color: #3730a3;
        margin: 15px 0;
        font-weight: 700;
        box-shadow: 0 4px 8px rgba(129,140,248,0.2);
    }
    
    .v-line {
        width: 3px;
        background: linear-gradient(180deg, #818cf8, #6366f1);
        margin: 0 auto;
        border-radius: 2px;
    }
</style>

<div class="tree-wrapper">
    @php
        function displayTree($node, $depth = 0) {
            if ($node['type'] === 'leaf') {
                $isYes = in_array(strtolower(trim($node['label'])), ['ya', 'yes', 'sukses', 'success', '1', 'true', 'beli']);
                $class = $isYes ? 'leaf-yes' : 'leaf-no';
                $icon = $isYes ? '✓' : '✗';
                
                echo "<div class='node-box {$class}'>";
                echo "<div class='node-content'>{$icon} {$node['label']}</div>";
                echo "<div class='node-info'>{$node['count']} data</div>";
                echo "</div>";
            } else {
                echo "<div style='text-align: center; display: inline-block;'>";
                
                // Decision node
                echo "<div class='node-box decision-box'>";
                echo "<div class='node-content'>📊 {$node['attribute']}</div>";
                echo "<div class='node-info'>Gain: " . number_format($node['gain'], 4) . "</div>";
                echo "</div>";
                
                // Garis ke bawah
                if (!empty($node['children'])) {
                    echo "<div style='margin: 8px 0;'>";
                    echo "<div class='v-line' style='height: 25px;'></div>";
                    echo "</div>";
                    
                    echo "<div class='tree-row'>";
                    
                    foreach ($node['children'] as $value => $child) {
                        echo "<div class='tree-column'>";
                        
                        // Label cabang
                        echo "<div class='edge-label'>{$value}</div>";
                        
                        // Garis ke child
                        echo "<div style='margin: 8px 0;'>";
                        echo "<div class='v-line' style='height: 20px;'></div>";
                        echo "</div>";
                        
                        // Child node
                        displayTree($child, $depth + 1);
                        
                        echo "</div>";
                    }
                    
                    echo "</div>";
                }
                
                echo "</div>";
            }
        }
        
        displayTree($tree);
    @endphp
</div>