@if($node['type'] === 'decision')
    <div style="margin-left: {{ $depth * 30 }}px; margin-top: 10px;">
        <span class="tree-node tree-decision">
            <strong>{{ $node['attribute'] }}</strong><br>
            <small>Gain: {{ number_format($node['gain'], 4) }}</small>
        </span>
        <div style="margin-top: 10px;">
            @foreach($node['children'] as $value => $child)
                <div style="margin-left: 20px; padding: 10px; border-left: 2px solid #999;">
                    <span style="color: #d32f2f; font-weight: bold;">{{ $value }}</span>
                    @include('tree-display', ['node' => $child, 'depth' => $depth + 1])
                </div>
            @endforeach
        </div>
    </div>
@else
    <span class="tree-node tree-leaf">
        <strong>{{ $node['label'] }}</strong><br>
        <small>({{ $node['count'] }} data)</small>
    </span>
@endif