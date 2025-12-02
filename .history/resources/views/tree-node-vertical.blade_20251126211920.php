@if($node['type'] === 'decision')
    <span class="decision"> → {{ $node['attribute'] }}</span>
    <span class="gain">(Gain: {{ number_format($node['gain'], 4) }})</span>
    
    @php
        $values = array_keys($node['children']);
        $count = count($values);
    @endphp

    @foreach($values as $index => $value)
        @php
            $child = $node['children'][$value];
            $nextIsLast = ($index == $count - 1);
            $nextConnector = $nextIsLast ? '└─' : '├─';
            $nextBranch = $nextIsLast ? '   ' : '│  ';
        @endphp

        <div class="tree-line">
            <span>{{ $prefix }}{{ $nextConnector }} <strong>{{ $value }}</strong></span>
            @include('tree-node-vertical', ['node' => $child, 'prefix' => $prefix . $nextBranch, 'isLast' => $nextIsLast])
        </div>
    @endforeach

@else
    {{-- Leaf Node --}}
    @if($node['label'] === 'Yes')
        <span class="result-yes"> → ✓ SUKSES</span>
    @else
        <span class="result-no"> → ✗ GAGAL</span>
    @endif
    <span class="count">({{ $node['count'] }} data)</span>
@endif