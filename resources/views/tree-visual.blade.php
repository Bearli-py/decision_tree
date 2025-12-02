<style>
    .tree-wrapper {
        padding: 50px;
        background: linear-gradient(135deg, #fafafa, #f3f4f6);
        border-radius: 16px;
        overflow-x: auto;
        border: 2px solid #e5e7eb;
    }
    
    .tree-level {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0;
    }
    
    .node-box {
        display: inline-block;
        padding: 20px 35px;
        border-radius: 14px;
        font-weight: 700;
        text-align: center;
        min-width: 160px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        position: relative;
    }
    
    .node-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.25);
    }
    
    .decision-box {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #78350f;
        border: 4px solid #d97706;
    }
    
    .leaf-yes {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        color: #064e3b;
        border: 4px solid #059669;
    }
    
    .leaf-no {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        color: #7f1d1d;
        border: 4px solid #dc2626;
    }
    
    .node-content { 
        font-size: 16px; 
        font-weight: 800; 
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    
    .node-info { 
        font-size: 12px; 
        opacity: 0.85; 
        font-weight: 600;
    }
    
    /* Garis penghubung vertikal */
    .vertical-line {
        width: 4px;
        height: 40px;
        background: linear-gradient(180deg, #6366f1 0%, #4f46e5 100%);
        margin: 0 auto;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(99,102,241,0.4);
    }
    
    /* Container horizontal untuk child nodes */
    .children-row {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: 20px;
        position: relative;
    }
    
    .child-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    /* Label edge */
    .edge-label {
        display: inline-block;
        background: linear-gradient(135deg, #ddd6fe, #c7d2fe);
        border: 3px solid #818cf8;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 14px;
        color: #3730a3;
        margin: 15px 0;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(129,140,248,0.3);
        letter-spacing: 0.5px;
    }
    
    /* Garis horizontal connector antar siblings */
    .horizontal-connector {
        position: absolute;
        top: -20px;
        height: 4px;
        background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 50%, #6366f1 100%);
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(99,102,241,0.4);
    }
    
    /* Garis vertikal dari horizontal line ke node */
    .branch-down {
        width: 4px;
        height: 20px;
        background: linear-gradient(180deg, #6366f1 0%, #4f46e5 100%);
        margin: 0 auto 0 auto;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(99,102,241,0.4);
    }
</style>

<div class="tree-wrapper">
    @php
        function renderTreeNode($node, $depth = 0) {
            echo '<div class="tree-level">';
            
            if ($node['type'] === 'leaf') {
                // Leaf node
                $isYes = in_array(strtolower(trim($node['label'])), ['ya', 'yes', 'sukses', 'success', '1', 'true', 'beli']);
                $class = $isYes ? 'leaf-yes' : 'leaf-no';
                $icon = $isYes ? '✓' : '✗';
                
                echo "<div class='node-box {$class}'>";
                echo "<div class='node-content'>{$icon} {$node['label']}</div>";
                echo "<div class='node-info'>{$node['count']} data</div>";
                echo "</div>";
                
            } else {
                // Decision node
                echo "<div class='node-box decision-box'>";
                echo "<div class='node-content'>📊 {$node['attribute']}</div>";
                echo "<div class='node-info'>Gain: " . number_format($node['gain'], 4) . "</div>";
                echo "</div>";
                
                if (!empty($node['children'])) {
                    // Garis vertikal ke bawah dari decision node
                    echo "<div class='vertical-line'></div>";
                    
                    // Container untuk semua children
                    echo "<div class='children-row'>";
                    
                    $childIndex = 0;
                    $childCount = count($node['children']);
                    
                    foreach ($node['children'] as $value => $child) {
                        echo "<div class='child-wrapper'>";
                        
                        // Garis vertikal pendek dari horizontal line ke label
                        if ($childCount > 1) {
                            echo "<div class='branch-down'></div>";
                        }
                        
                        // Label edge (nama nilai atribut)
                        echo "<div class='edge-label'>{$value}</div>";
                        
                        // Garis vertikal dari label ke child node
                        echo "<div class='vertical-line' style='height: 25px;'></div>";
                        
                        // Recursive render child
                        renderTreeNode($child, $depth + 1);
                        
                        echo "</div>";
                        $childIndex++;
                    }
                    
                    // Garis horizontal penghubung antar siblings (jika lebih dari 1 child)
                    if ($childCount > 1) {
                        echo "<div class='horizontal-connector' style='left: 0; right: 0;'></div>";
                    }
                    
                    echo "</div>";
                }
            }
            
            echo '</div>';
        }
        
        renderTreeNode($tree);
    @endphp
</div>