<style>
    .tree-container { 
        padding: 30px; 
        background: #0a0a0a; 
        border-radius: 8px; 
        overflow-x: auto;
        border: 1px solid #333;
    }
    
    .tree-node {
        display: inline-block;
        padding: 12px 20px;
        margin: 10px;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        min-width: 120px;
        position: relative;
    }
    
    .decision-node {
        background: linear-gradient(135deg, #d4a017 0%, #edb82f 100%);
        color: #000;
        border: 2px solid #d4a017;
    }
    
    .leaf-success {
        background: linear-gradient(135deg, #10b981 0%, #4ade80 100%);
        color: #000;
        border: 2px solid #10b981;
    }
    
    .leaf-fail {
        background: linear-gradient(135deg, #dc2626 0%, #f87171 100%);
        color: #fff;
        border: 2px solid #dc2626;
    }
    
    .tree-level {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        flex-wrap: wrap;
        margin: 20px 0;
    }
    
    .tree-branch {
        margin: 0 15px;
        text-align: center;
    }
    
    .branch-label {
        display: block;
        margin: 10px 0;
        padding: 5px 10px;
        background: #1a1a1a;
        border-radius: 4px;
        font-size: 12px;
        color: #888;
        border: 1px solid #333;
    }
    
    .gain-badge {
        display: block;
        font-size: 10px;
        margin-top: 5px;
        color: #888;
        font-weight: normal;
    }
</style>

<div class="tree-container">
    @php
        function renderNode($node, $depth = 0) {
            if ($node['type'] === 'leaf') {
                $isSuccess = in_array(strtolower($node['label']), ['ya', 'yes', 'sukses', 'success', '1', 'true']);
                $class = $isSuccess ? 'leaf-success' : 'leaf-fail';
                $icon = $isSuccess ? '✓' : '✗';
                
                echo "<div class='tree-node {$class}'>";
                echo "<div>{$icon} {$node['label']}</div>";
                echo "<span class='gain-badge'>({$node['count']} data)</span>";
                echo "</div>";
            } else {
                echo "<div style='text-align: center;'>";
                echo "<div class='tree-node decision-node'>";
                echo "<div>{$node['attribute']}</div>";
                echo "<span class='gain-badge'>Gain: " . number_format($node['gain'], 4) . "</span>";
                echo "</div>";
                
                if (!empty($node['children'])) {
                    echo "<div class='tree-level'>";
                    foreach ($node['children'] as $value => $child) {
                        echo "<div class='tree-branch'>";
                        echo "<span class='branch-label'>{$value}</span>";
                        renderNode($child, $depth + 1);
                        echo "</div>";
                    }
                    echo "</div>";
                }
                echo "</div>";
            }
        }
        
        renderNode($tree);
    @endphp
</div>