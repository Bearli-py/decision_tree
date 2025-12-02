<?php

namespace App\Services;

class DecisionTreeService
{
    protected CalculationService $calculationService;

    public function __construct(CalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Build decision tree
     */
    public function buildTree(array $data, string $targetAttribute, ?array $attributes = null): array
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Dataset kosong.');
        }

        $firstRow = reset($data);

        if ($attributes === null) {
            $attributes = array_values(
                array_filter(
                    array_keys($firstRow),
                    fn($col) => $col !== $targetAttribute
                )
            );
        }

        return $this->buildTreeRecursive($data, $attributes, $targetAttribute);
    }

    /**
     * Recursive tree building
     */
    protected function buildTreeRecursive(
        array $data,
        array $attributes,
        string $targetAttribute,
        int $depth = 0
    ): array {
        // Hitung distribusi kelas
        $classCounts = [];
        foreach ($data as $row) {
            $label = (string) $row[$targetAttribute];
            $classCounts[$label] = ($classCounts[$label] ?? 0) + 1;
        }

        $total = array_sum($classCounts);

        // Semua data punya kelas sama -> leaf
        if (count($classCounts) === 1) {
            $label = array_key_first($classCounts);

            return [
                'type'         => 'leaf',
                'label'        => $label,
                'count'        => $total,
                'distribution' => $classCounts,
            ];
        }

        // Tidak ada atribut tersisa -> leaf mayoritas
        if (empty($attributes)) {
            $majorityLabel = array_keys($classCounts, max($classCounts))[0];

            return [
                'type'         => 'leaf',
                'label'        => $majorityLabel,
                'count'        => $total,
                'distribution' => $classCounts,
            ];
        }

        // Pilih atribut terbaik
        $bestAttribute = null;
        $maxGain       = -1.0;

        foreach ($attributes as $attr) {
            $subsets = $this->groupByAttribute($data, $attr);

            if (count($subsets) <= 1) {
                continue;
            }

            $gain = $this->calculationService->calculateGain($data, $attr, $targetAttribute);

            if ($gain > $maxGain) {
                $maxGain       = $gain;
                $bestAttribute = $attr;
            }
        }

        // Tidak ada atribut berguna -> leaf mayoritas
        if ($bestAttribute === null || $maxGain <= 0) {
            $majorityLabel = array_keys($classCounts, max($classCounts))[0];

            return [
                'type'         => 'leaf',
                'label'        => $majorityLabel,
                'count'        => $total,
                'distribution' => $classCounts,
            ];
        }

        // Split berdasarkan atribut terbaik
        $subsets        = $this->groupByAttribute($data, $bestAttribute);
        $remainingAttrs = array_values(array_diff($attributes, [$bestAttribute]));

        $children = [];
        foreach ($subsets as $value => $subset) {
            $children[$value] = $this->buildTreeRecursive(
                $subset,
                $remainingAttrs,
                $targetAttribute,
                $depth + 1
            );
        }

        return [
            'type'         => 'decision',
            'attribute'    => $bestAttribute,
            'gain'         => $maxGain,
            'children'     => $children,
            'count'        => $total,
            'distribution' => $classCounts,
        ];
    }

    protected function groupByAttribute(array $data, string $attribute): array
    {
        $groups = [];

        foreach ($data as $row) {
            if (!array_key_exists($attribute, $row)) {
                continue;
            }

            $value = $row[$attribute];
            $groups[$value][] = $row;
        }

        return $groups;
    }
}