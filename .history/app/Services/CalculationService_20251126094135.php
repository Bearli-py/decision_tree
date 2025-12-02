<?php

namespace App\Services;

class CalculationService
{
    /**
     * Calculate entropy of dataset
     */
    public function calculateEntropy($data, $labelField = 'Play')
    {
        if (empty($data)) return 0;

        $yesCount = count(array_filter($data, fn($item) => $item[$labelField] === 'yes'));
        $noCount = count($data) - $yesCount;
        $total = count($data);

        if ($yesCount == 0 || $noCount == 0) return 0;

        $pYes = $yesCount / $total;
        $pNo = $noCount / $total;

        return -($pYes * log($pYes, 2) + $pNo * log($pNo, 2));
    }

    /**
     * Calculate Information Gain untuk atribut
     */
    public function calculateGain($data, $attribute, $labelField = 'Play')
    {
        $parentEntropy = $this->calculateEntropy($data, $labelField);
        
        // Group data by attribute values
        $subsets = $this->groupByAttribute($data, $attribute);
        
        $weightedEntropy = 0;
        $totalCount = count($data);

        foreach ($subsets as $subset) {
            $weight = count($subset) / $totalCount;
            $weightedEntropy += $weight * $this->calculateEntropy($subset, $labelField);
        }

        return $parentEntropy - $weightedEntropy;
    }

    /**
     * Calculate Split Information (untuk Gain Ratio)
     */
    public function calculateSplitInfo($data, $attribute)
    {
        $subsets = $this->groupByAttribute($data, $attribute);
        $totalCount = count($data);
        $splitInfo = 0;

        foreach ($subsets as $subset) {
            $proportion = count($subset) / $totalCount;
            if ($proportion > 0) {
                $splitInfo -= $proportion * log($proportion, 2);
            }
        }

        return $splitInfo;
    }

    /**
     * Calculate Gain Ratio
     */
    public function calculateGainRatio($data, $attribute, $labelField = 'Play')
    {
        $gain = $this->calculateGain($data, $attribute, $labelField);
        $splitInfo = $this->calculateSplitInfo($data, $attribute);

        if ($splitInfo == 0) return 0;
        return $gain / $splitInfo;
    }

    /**
     * Group data by attribute values
     */
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

    /**
     * Get all unique attributes (excluding label)
     */
    public function getAttributes($data)
    {
        if (empty($data)) return [];
        
        $keys = array_keys($data[0]);
        return array_diff($keys, ['No', 'Play']);
    }

    /**
     * Calculate all metrics untuk dataset (output untuk tabel)
     */
    public function calculateAllMetrics($data, $labelField = 'Play')
    {
        $attributes = $this->getAttributes($data);
        $yesCount = count(array_filter($data, fn($item) => $item[$labelField] === 'yes'));
        $noCount = count($data) - $yesCount;

        $metrics = [
            'total_data' => count($data),
            'yes_count' => $yesCount,
            'no_count' => $noCount,
            'entropy' => $this->calculateEntropy($data, $labelField),
            'attributes' => []
        ];

        // Calculate metrics per attribute
        foreach ($attributes as $attr) {
            $subsets = $this->groupByAttribute($data, $attr);
            $attrMetrics = [
                'name' => $attr,
                'values' => [],
                'gain' => $this->calculateGain($data, $attr, $labelField),
                'split_info' => $this->calculateSplitInfo($data, $attr),
                'gain_ratio' => $this->calculateGainRatio($data, $attr, $labelField)
            ];

            // Calculate metrics per value
            foreach ($subsets as $value => $subset) {
                $valueYes = count(array_filter($subset, fn($item) => $item[$labelField] === 'yes'));
                $valueNo = count($subset) - $valueYes;

                $attrMetrics['values'][] = [
                    'value' => $value,
                    'count' => count($subset),
                    'yes_count' => $valueYes,
                    'no_count' => $valueNo,
                    'entropy' => $this->calculateEntropy($subset, $labelField)
                ];
            }

            $metrics['attributes'][] = $attrMetrics;
        }

        return $metrics;
    }
}