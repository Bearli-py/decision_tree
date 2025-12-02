<style>
    .tree-container {
        background: #f9f9f9;
        padding: 30px;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 14px;
        line-height: 1.8;
        overflow-x: auto;
        border: 1px solid #ddd;
    }

    .tree-line {
        margin: 5px 0;
    }

    .decision {
        color: #d32f2f;
        font-weight: bold;
    }

    .result-yes {
        color: #388e3c;
        font-weight: bold;
    }

    .result-no {
        color: #d32f2f;
        font-weight: bold;
    }

    .gain {
        color: #666;
        font-size: 12px;
    }

    .count {
        color: #999;
        font-size: 12px;
    }

    .branch {
        margin-left: 20px;
    }

    .connector {
        color: #999;
    }
</style>

<div class="tree-container">
    <div class="tree-line">
        <span class="decision">{{ $tree['attribute'] }}</span>
        <span class="gain">(Gain: {{ number_format($tree['gain'], 4) }})</span>
    </div>

    @php
        $values = array_keys($tree['children']);
        $count = count($values);
    @endphp

    @foreach($values as $index => $value)
        @php
            $child = $tree['children'][$value];
            $isLast = ($index == $count - 1);
            $connector = $isLast ? '└─' : '├─';
            $branch = $isLast ? '   ' : '│  ';
        @endphp

        <div class="tree-line">
            <span class="connector">{{ $connector }}</span>
            <strong>{{ $value }}</strong>
            @include('tree-node-vertical', ['node' => $child, 'prefix' => $branch, 'isLast' => $isLast])
        </div>
    @endforeach
</div>