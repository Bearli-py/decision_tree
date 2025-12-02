<?php

namespace App\Services;

class DecisionTreeService
{
    protected $calculationService;

    public function __construct(CalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Build tree dari dynamic data
     */
    public function buildTree($data)
    {
        $attributes = ['Peserta', 'Budget', 'Speaker', 'Topik'];
        return $this->buildTreeRecursive($data, $attributes);
    }

    /**
     * Recursive function untuk build tree
     */
    protected function buildTreeRecursive($data, $attributes, $depth = 0)
    {
        // Check if data is pure (semua yes atau semua no)
        $yesCount = count(array_filter($data, fn($item) => $item['Play'] === 'yes'));
        $noCount = count($data) - $yesCount;

        if ($yesCount == 0) {
            return [
                'type' => 'leaf',
                'label' => 'No',
                'count' => count($data)
            ];
        }

        if ($noCount == 0) {
            return [
                'type' => 'leaf',
                'label' => 'Yes',
                'count' => count($data)
            ];
        }

        // Check if no more attributes
        if (empty($attributes)) {
            $majority = $yesCount > $noCount ? 'Yes' : 'No';
            return [
                'type' => 'leaf',
                'label' => $majority,
                'count' => count($data)
            ];
        }

        // Find best attribute (highest gain)
        $bestAttribute = null;
        $maxGain = -1;

        foreach ($attributes as $attr) {
            $gain = $this->calculationService->calculateGain($data, $attr);
            if ($gain > $maxGain) {
                $maxGain = $gain;
                $bestAttribute = $attr;
            }
        }

        if ($bestAttribute === null) {
            $majority = $yesCount > $noCount ? 'Yes' : 'No';
            return [
                'type' => 'leaf',
                'label' => $majority,
                'count' => count($data)
            ];
        }

        // Split data by best attribute
        $subsets = $this->groupByAttribute($data, $bestAttribute);
        $remainingAttrs = array_diff($attributes, [$bestAttribute]);

        $children = [];
        foreach ($subsets as $value => $subset) {
            $children[$value] = $this->buildTreeRecursive($subset, $remainingAttrs, $depth + 1);
        }

        return [
            'type' => 'decision',
            'attribute' => $bestAttribute,
            'gain' => $maxGain,
            'children' => $children,
            'count' => count($data)
        ];
    }

    protected function groupByAttribute($data, $attribute)
    {
        $groups = [];
        foreach ($data as $row) {
            $value = $row[$attribute];
            if (!isset($groups[$value])) {
                $groups[$value] = [];
            }
            $groups[$value][] = $row;
        }
        return $groups;
    }
    public function results()
{
    $data = session('dataset');
    $metrics = session('metrics');
    $tree = session('tree');
    $fileName = session('file_name', 'unknown.csv');

    if (!$data || !$metrics || !$tree) {
        return redirect('/');
    }

    return view('results', compact('data', 'metrics', 'tree', 'fileName'));
}
}