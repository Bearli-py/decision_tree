<?php

namespace App\Services;

class CalculationService
{
    /**
     * Calculate entropy
     */
    public function calculateEntropy($data)
    {
        if (empty($data)) return 0;

        $yesCount = count(array_filter($data, fn($item) => $item['Play'] === 'yes'));
        $noCount = count($data) - $yesCount;
        $total = count($data);

        if ($yesCount == 0 || $noCount == 0) return 0;

        $pYes = $yesCount / $total;
        $pNo = $noCount / $total;

        return -($pYes * log($pYes, 2) + $pNo * log($pNo, 2));
    }

    /**
     * Calculate Information Gain
     */
    public function calculateGain($data, $attribute)
    {
        $parentEntropy = $this->calculateEntropy($data);
        
        $subsets = $this->groupByAttribute($data, $attribute);
        
        $weightedEntropy = 0;
        $totalCount = count($data);

        foreach ($subsets as $subset) {
            $weight = count($subset) / $totalCount;
            $weightedEntropy += $weight * $this->calculateEntropy($subset);
        }

        return $parentEntropy - $weightedEntropy;
    }

    /**
     * Calculate Split Info (untuk Gain Ratio)
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
    public function calculateGainRatio($data, $attribute)
    {
        $gain = $this->calculateGain($data, $attribute);
        $splitInfo = $this->calculateSplitInfo($data, $attribute);

        if ($splitInfo == 0) return 0;
        return $gain / $splitInfo;
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

    /**
     * Calculate all metrics (untuk display di tabel)
     */
    public function calculateAllMetrics($data)
    {
        $attributes = ['Peserta', 'Budget', 'Speaker', 'Topik'];
        $yesCount = count(array_filter($data, fn($item) => $item['Play'] === 'yes'));
        $noCount = count($data) - $yesCount;

        $metrics = [
            'total_data' => count($data),
            'yes_count' => $yesCount,
            'no_count' => $noCount,
            'entropy' => $this->calculateEntropy($data),
            'attributes' => []
        ];

        foreach ($attributes as $attr) {
            $subsets = $this->groupByAttribute($data, $attr);
            
            $attrMetrics = [
                'name' => $attr,
                'values' => [],
                'gain' => $this->calculateGain($data, $attr),
                'split_info' => $this->calculateSplitInfo($data, $attr),
                'gain_ratio' => $this->calculateGainRatio($data, $attr)
            ];

            foreach ($subsets as $value => $subset) {
                $valueYes = count(array_filter($subset, fn($item) => $item['Play'] === 'yes'));
                $valueNo = count($subset) - $valueYes;

                $attrMetrics['values'][] = [
                    'value' => $value,
                    'count' => count($subset),
                    'yes_count' => $valueYes,
                    'no_count' => $valueNo,
                    'entropy' => $this->calculateEntropy($subset)
                ];
            }

            $metrics['attributes'][] = $attrMetrics;
        }

        return $metrics;
    }
}