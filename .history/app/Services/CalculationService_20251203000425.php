<?php

namespace App\Services;

class CalculationService
{
    /**
     * Hitung entropy - FORMULA STANDAR ID3/C4.5
     * Entropy(S) = -Σ(p_i * log2(p_i))
     */
    public function calculateEntropy(array $data, string $targetAttribute): float
    {
        if (empty($data)) {
            return 0.0;
        }

        $counts = [];
        foreach ($data as $row) {
            if (!array_key_exists($targetAttribute, $row)) {
                throw new \InvalidArgumentException("Kolom '{$targetAttribute}' tidak ada.");
            }
            $label = trim((string) $row[$targetAttribute]); // Trim untuk menghindari spasi
            $counts[$label] = ($counts[$label] ?? 0) + 1;
        }

        $total = array_sum($counts);
        if ($total === 0) return 0.0;

        $entropy = 0.0;
        foreach ($counts as $count) {
            if ($count > 0) {
                $p = $count / $total;
                $entropy -= $p * log($p, 2);
            }
        }

        return $entropy;
    }

    /**
     * Information Gain - FORMULA STANDAR
     * Gain(S, A) = Entropy(S) - Σ(|Sv|/|S| * Entropy(Sv))
     */
    public function calculateGain(array $data, string $attribute, string $targetAttribute): float
    {
        if (empty($data)) return 0.0;

        $parentEntropy = $this->calculateEntropy($data, $targetAttribute);
        $subsets = $this->groupByAttribute($data, $attribute);

        if (count($subsets) <= 1) return 0.0;

        $totalCount = count($data);
        $weightedEntropy = 0.0;

        foreach ($subsets as $subset) {
            $weight = count($subset) / $totalCount;
            $weightedEntropy += $weight * $this->calculateEntropy($subset, $targetAttribute);
        }

        return $parentEntropy - $weightedEntropy;
    }

    /**
     * Split Info - untuk C4.5 Gain Ratio
     * SplitInfo(S, A) = -Σ(|Sv|/|S| * log2(|Sv|/|S|))
     */
    public function calculateSplitInfo(array $data, string $attribute): float
    {
        $subsets = $this->groupByAttribute($data, $attribute);
        $total = count($data);

        if ($total === 0 || count($subsets) <= 1) return 0.0;

        $splitInfo = 0.0;
        foreach ($subsets as $subset) {
            $p = count($subset) / $total;
            if ($p > 0) {
                $splitInfo -= $p * log($p, 2);
            }
        }

        return $splitInfo;
    }

    /**
     * Gain Ratio - C4.5 Algorithm
     * GainRatio(S, A) = Gain(S, A) / SplitInfo(S, A)
     */
    public function calculateGainRatio(array $data, string $attribute, string $targetAttribute): float
    {
        $gain = $this->calculateGain($data, $attribute, $targetAttribute);
        $splitInfo = $this->calculateSplitInfo($data, $attribute);

        // Avoid division by zero
        if ($splitInfo == 0.0) return 0.0;

        return $gain / $splitInfo;
    }

    protected function groupByAttribute(array $data, string $attribute): array
    {
        $groups = [];
        foreach ($data as $row) {
            if (!array_key_exists($attribute, $row)) continue;
            
            // Trim untuk menghindari masalah spasi
            $value = trim((string) $row[$attribute]);
            $groups[$value][] = $row;
        }
        return $groups;
    }

    /**
     * Calculate all metrics dengan format detail untuk debugging
     */
    public function calculateAllMetrics(array $data, string $targetAttribute, ?array $attributes = null): array
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Dataset kosong.');
        }

        $firstRow = reset($data);

        if ($attributes === null) {
            $attributes = array_values(
                array_filter(
                    array_keys($firstRow),
                    fn($col) => $col !== $targetAttribute && strtolower($col) !== 'no'
                )
            );
        }

        // Distribusi kelas
        $classCounts = [];
        foreach ($data as $row) {
            $label = trim((string) $row[$targetAttribute]);
            $classCounts[$label] = ($classCounts[$label] ?? 0) + 1;
        }

        $metrics = [
            'total_data' => count($data),
            'target' => $targetAttribute,
            'class_counts' => $classCounts,
            'entropy' => $this->calculateEntropy($data, $targetAttribute),
            'attributes' => [],
        ];

        foreach ($attributes as $attr) {
            $subsets = $this->groupByAttribute($data, $attr);

            if (count($subsets) <= 1) continue;

            $attrMetrics = [
                'name' => $attr,
                'values' => [],
                'gain' => $this->calculateGain($data, $attr, $targetAttribute),
                'split_info' => $this->calculateSplitInfo($data, $attr),
                'gain_ratio' => $this->calculateGainRatio($data, $attr, $targetAttribute),
            ];

            foreach ($subsets as $value => $subset) {
                $subsetCounts = [];
                foreach ($subset as $row) {
                    $label = trim((string) $row[$targetAttribute]);
                    $subsetCounts[$label] = ($subsetCounts[$label] ?? 0) + 1;
                }

                $attrMetrics['values'][] = [
                    'value' => $value,
                    'count' => count($subset),
                    'class_counts' => $subsetCounts,
                    'entropy' => $this->calculateEntropy($subset, $targetAttribute),
                ];
            }

            $metrics['attributes'][] = $attrMetrics;
        }

        return $metrics;
    }
}