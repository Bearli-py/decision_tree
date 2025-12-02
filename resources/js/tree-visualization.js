function visualizeTree(treeData, containerId) {
    const container = d3.select(containerId);
    container.html(''); // Clear previous

    // Calculate dimensions
    const width = Math.max(800, document.querySelector(containerId).offsetWidth - 40);
    const height = 600;

    // Create SVG
    const svg = container.append('svg')
        .attr('width', width)
        .attr('height', height)
        .style('border', '1px solid #ddd')
        .style('border-radius', '8px');

    const g = svg.append('g')
        .attr('transform', `translate(${width/2}, 50)`);

    // Tree layout
    const tree = d3.tree().size([width - 100, height - 100]);
    const root = d3.hierarchy(convertTreeStructure(treeData));
    tree(root);

    // Draw links
    g.selectAll('path')
        .data(root.links())
        .enter()
        .append('path')
        .attr('d', d3.linkVertical()
            .x(d => d.x)
            .y(d => d.y))
        .style('stroke', '#999')
        .style('stroke-width', 2)
        .style('fill', 'none');

    // Draw nodes
    const nodes = g.selectAll('g.node')
        .data(root.descendants())
        .enter()
        .append('g')
        .attr('class', 'node')
        .attr('transform', d => `translate(${d.x}, ${d.y})`);

    // Node rectangles
    nodes.append('rect')
        .attr('width', 120)
        .attr('height', 60)
        .attr('x', -60)
        .attr('y', -30)
        .attr('rx', 8)
        .style('fill', d => d.data.type === 'leaf' ? (d.data.label === 'Yes' ? '#28a745' : '#dc3545') : '#0070C0')
        .style('stroke', '#333')
        .style('stroke-width', 2)
        .style('cursor', 'pointer')
        .on('mouseover', function() {
            d3.select(this).style('opacity', 0.8);
        })
        .on('mouseout', function() {
            d3.select(this).style('opacity', 1);
        });

    // Node text
    nodes.append('text')
        .attr('dy', '-5')
        .attr('text-anchor', 'middle')
        .style('font-weight', 'bold')
        .style('font-size', '12px')
        .style('fill', 'white')
        .text(d => d.data.type === 'decision' ? d.data.attribute : d.data.label);

    // Node count
    nodes.append('text')
        .attr('dy', '15')
        .attr('text-anchor', 'middle')
        .style('font-size', '11px')
        .style('fill', 'white')
        .text(d => `(${d.data.count})`);
}

function convertTreeStructure(treeData) {
    if (treeData.type === 'leaf') {
        return {
            name: treeData.label,
            type: 'leaf',
            label: treeData.label,
            count: treeData.count,
            children: []
        };
    }

    const children = [];
    for (const [value, child] of Object.entries(treeData.children || {})) {
        children.push({
            name: `${value}`,
            value: value,
            ...convertTreeStructure(child)
        });
    }

    return {
        name: treeData.attribute,
        type: 'decision',
        attribute: treeData.attribute,
        count: treeData.count,
        gain: treeData.gain,
        children: children
    };
}