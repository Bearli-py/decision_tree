<style>
    .tree-wrapper {
        padding: 40px;
        background: #0a0a0a;
        border-radius: 8px;
        overflow-x: auto;
        border: 1px solid #333;
        position: relative;
    }
    
    .node-box {
        display: inline-block;
        padding: 15px 25px;
        margin: 15px;
        border-radius: 10px;
        font-weight: 600;
        text-align: center;
        min-width: 140px;
        position: relative;
        box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    }
    
    .decision-box {
        background: linear-gradient(135deg, #f7b801 0%, #d4a017 100%);
        color: #000;
        border: 3px solid #d4a017;
    }
    
    .leaf-yes {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        border: 3px solid #10b981;
    }
    
    .leaf-no {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
        border: 3px solid #dc2626;
    }
    
    .node-content { font-size: 14px; font-weight: 700; }
    .node-info { font-size: 11px; margin-top: 5px; opacity: 0.8; font-weight: 400; }
    
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
        background: #1a1a1a;
        border: 1px solid #444;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 12px;
        color: #aaa;
        margin: 15px 0;
        font-weight: 600;
    }
    
    .connector-line {
        position: absolute;
        background: #444;
    }
    
    .v-line { width: 2px; left: 50%; transform: translateX(-50%); }
    .h-line { height: 2px; top: 0; }
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
                
                // Root atau decision node
                echo "<div class='node-box decision-box'>";
                echo "<div class='node-content'>{$node['attribute']}</div>";
                echo "<div class='node-info'>Gain: " . number_format($node['gain'], 4) . "</div>";
                echo "</div>";
                
                // Garis ke bawah
                if (!empty($node['children'])) {
                    echo "<div style='position: relative; margin: 5px 0;'>";
                    echo "<div class='v-line' style='height: 20px; background: #555;'></div>";
                    echo "</div>";
                    
                    echo "<div class='tree-row'>";
                    
                    $childCount = count($node['children']);
                    $index = 0;
                    
                    foreach ($node['children'] as $value => $child) {
                        echo "<div class='tree-column'>";
                        
                        // Label cabang
                        echo "<div class='edge-label'>{$value}</div>";
                        
                        // Garis ke node child
                        echo "<div style='position: relative; margin: 5px 0;'>";
                        echo "<div class='v-line' style='height: 15px; background: #555;'></div>";
                        echo "</div>";
                        
                        // Child node
                        displayTree($child, $depth + 1);
                        
                        echo "</div>";
                        $index++;
                    }
                    
                    echo "</div>";
                }
                
                echo "</div>";
            }
        }
        
        displayTree($tree);
    @endphp
</div>