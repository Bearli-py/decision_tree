// app/Services/CalculationService.php

namespace App\Services;

class CalculationService
{
    /**
     * Hitung entropy untuk suatu kolom target.
     */
    public function calculateEntropy(array $data, string $targetAttribute): float
    {
        if (empty($data)) {
            return 0.0;
        }

        $counts = [];

        foreach ($data as $row) {
            if (!array_key_exists($targetAttribute, $row)) {
                throw new \InvalidArgumentException(
                    "Kolom target '{$targetAttribute}' tidak ditemukan di salah satu baris data."
                );
            }

            $label = (string) $row[$targetAttribute];
            $counts[$label] = ($counts[$label] ?? 0) + 1;
        }

        $total = array_sum($counts);
        if ($total === 0) {
            return 0.0;
        }

        $entropy = 0.0;
        foreach ($counts as $count) {
            $p = $count / $total;
            if ($p > 0) {
                $entropy -= $p * log($p, 2);
            }
        }

        return $entropy;
    }

    /**
     * Hitung Information Gain untuk satu atribut terhadap target.
     */
    public function calculateGain(array $data, string $attribute, string $targetAttribute): float
    {
        if (empty($data)) {
            return 0.0;
        }

        $parentEntropy = $this->calculateEntropy($data, $targetAttribute);
        $subsets       = $this->groupByAttribute($data, $attribute);

        // Kalau atribut hanya punya 1 nilai unik, gain = 0 (split tidak berguna)
        if (count($subsets) <= 1) {
            return 0.0;
        }

        $weightedEntropy = 0.0;
        $totalCount      = count($data);

        foreach ($subsets as $subset) {
            $weight          = count($subset) / $totalCount;
            $weightedEntropy += $weight * $this->calculateEntropy($subset, $targetAttribute);
        }

        return $parentEntropy - $weightedEntropy;
    }

    /**
     * Split Info untuk Gain Ratio.
     */
    public function calculateSplitInfo(array $data, string $attribute): float
    {
        $subsets = $this->groupByAttribute($data, $attribute);
        $total   = count($data);

        if ($total === 0 || count($subsets) <= 1) {
            return 0.0;
        }

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
     * Gain Ratio untuk satu atribut.
     */
    public function calculateGainRatio(array $data, string $attribute, string $targetAttribute): float
    {
        $gain      = $this->calculateGain($data, $attribute, $targetAttribute);
        $splitInfo = $this->calculateSplitInfo($data, $attribute);

        if ($splitInfo == 0.0) {
            return 0.0;
        }

        return $gain / $splitInfo;
    }

    /**
     * Group data berdasarkan nilai suatu atribut.
     */
    protected function groupByAttribute(array $data, string $attribute): array
    {
        $groups = [];

        foreach ($data as $row) {
            if (!array_key_exists($attribute, $row)) {
                // Bisa juga dibuat throw jika mau lebih ketat
                continue;
            }

            $value = $row[$attribute];
            $groups[$value][] = $row;
        }

        return $groups;
    }

    /**
     * Hitung semua metrik untuk semua atribut (dinamis).
     *
     * $attributes bisa null -> otomatis semua kolom kecuali target.
     */
    public function calculateAllMetrics(
        array $data,
        string $targetAttribute,
        ?array $attributes = null
    ): array {
        if (empty($data)) {
            throw new \InvalidArgumentException('Dataset kosong, tidak bisa menghitung metrik.');
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

        // Distribusi kelas global
        $classCounts = [];
        foreach ($data as $row) {
            $label = (string) $row[$targetAttribute];
            $classCounts[$label] = ($classCounts[$label] ?? 0) + 1;
        }

        $metrics = [
            'total_data'   => count($data),
            'target'       => $targetAttribute,
            'class_counts' => $classCounts,
            'entropy'      => $this->calculateEntropy($data, $targetAttribute),
            'attributes'   => [],
        ];

        foreach ($attributes as $attr) {
            $subsets = $this->groupByAttribute($data, $attr);

            // Skip atribut yang nilai uniknya cuma 1
            if (count($subsets) <= 1) {
                continue;
            }

            $attrMetrics = [
                'name'       => $attr,
                'values'     => [],
                'gain'       => $this->calculateGain($data, $attr, $targetAttribute),
                'split_info' => $this->calculateSplitInfo($data, $attr),
                'gain_ratio' => $this->calculateGainRatio($data, $attr, $targetAttribute),
            ];

            foreach ($subsets as $value => $subset) {
                $subsetCounts = [];
                foreach ($subset as $row) {
                    $label = (string) $row[$targetAttribute];
                    $subsetCounts[$label] = ($subsetCounts[$label] ?? 0) + 1;
                }

                $attrMetrics['values'][] = [
                    'value'        => $value,
                    'count'        => count($subset),
                    'class_counts' => $subsetCounts,
                    'entropy'      => $this->calculateEntropy($subset, $targetAttribute),
                ];
            }

            $metrics['attributes'][] = $attrMetrics;
        }

        return $metrics;
    }
}
