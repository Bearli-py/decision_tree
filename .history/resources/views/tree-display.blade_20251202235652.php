<style>
    .tree-visual { font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.8; }
    .tree-node { margin: 8px 0; }
    .decision-node { color: #d4a017; font-weight: bold; }
    .leaf-yes { color: #4ade80; font-weight: bold; }
    .leaf-no { color: #f87171; font-weight: bold; }
    .gain-info { color: #888; font-size: 11px; margin-left: 8px; }
    .branch { margin-left: 30px; border-left: 2px solid #333; padding-left: 15px; }
    .connector { color: #666; margin-right: 5px; }
</style>

<div class="tree-visual">
    @if($tree['type'] === 'decision')
        <div class="tree-node">
            <span class="decision-node">📊 {{ $tree['attribute'] }}</span>
            <span class="gain-info">(Gain: {{ number_format($tree['gain'], 4) }})</span>
        </div>
        
        @foreach($tree['children'] as $value => $child)
            <div class="branch">
                <div style="margin-bottom: 5px;">
                    <span class="connector">├─</span>
                    <strong>{{ $value }}</strong>
                </div>
                @include('tree-display', ['tree' => $child])
            </div>
        @endforeach
    @else
        <div class="tree-node">
            @if($tree['label'] === 'Ya' || $tree['label'] === 'yes')
                <span class="leaf-yes">✓ {{ $tree['label'] }}</span>
            @else
                <span class="leaf-no">✗ {{ $tree['label'] }}</span>
            @endif
            <span class="gain-info">({{ $tree['count'] }} data)</span>
        </div>
    @endif
</div>