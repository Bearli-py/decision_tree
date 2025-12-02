<?php

namespace App\Services;

use App\Models\EventData;

class DecisionTreeService
{
    /**
     * Calculate entropy of a dataset
     */
    public function calculateEntropy($data)
    {
        if (empty($data)) return 0;
        
        $total = count($data);
        $klasifikasi = [];
        
        // Count each class
        foreach ($data as $item) {
            $hasil = $item['hasil'];
            $klasifikasi[$hasil] = ($klasifikasi[$hasil] ?? 0) + 1;
        }
        
        $entropy = 0;
        foreach ($klasifikasi as $count) {
            $p = $count / $total;
            if ($p > 0) {
                $entropy -= $p * log($p, 2);
            }
        }
        
        return $entropy;
    }
    
    /**
     * Calculate Information Gain for an attribute
     */
    public function calculateInformationGain($data, $attribute)
    {
        $parentEntropy = $this->calculateEntropy($data);
        $subsets = [];
        
        // Split data by attribute values
        foreach ($data as $item) {
            $value = $item[$attribute];
            if (!isset($subsets[$value])) {
                $subsets[$value] = [];
            }
            $subsets[$value][] = $item;
        }
        
        // Calculate weighted entropy of subsets
        $weightedEntropy = 0;
        $totalCount = count($data);
        
        foreach ($subsets as $subset) {
            $weight = count($subset) / $totalCount;
            $weightedEntropy += $weight * $this->calculateEntropy($subset);
        }
        
        return $parentEntropy - $weightedEntropy;
    }
    
    /**
     * Find best attribute to split
     */
    public function findBestAttribute($data, $attributes)
    {
        $bestAttribute = null;
        $maxIG = -1;
        
        foreach ($attributes as $attribute) {
            $ig = $this->calculateInformationGain($data, $attribute);
            
            if ($ig > $maxIG) {
                $maxIG = $ig;
                $bestAttribute = $attribute;
            }
        }
        
        return [
            'attribute' => $bestAttribute,
            'ig' => $maxIG
        ];
    }
    
    /**
     * Check if dataset is pure (all same class)
     */
    public function isPure($data)
    {
        if (empty($data)) return true;
        
        $firstHasil = $data[0]['hasil'];
        foreach ($data as $item) {
            if ($item['hasil'] !== $firstHasil) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get majority class
     */
    public function getMajorityClass($data)
    {
        if (empty($data)) return null;
        
        $klasifikasi = [];
        foreach ($data as $item) {
            $hasil = $item['hasil'];
            $klasifikasi[$hasil] = ($klasifikasi[$hasil] ?? 0) + 1;
        }
        
        return array_key_first($klasifikasi);
    }
    
    /**
     * Build Decision Tree Recursively
     */
    public function buildTree($data, $attributes, $depth = 0)
    {
        // Base case 1: Data is pure
        if ($this->isPure($data)) {
            return [
                'type' => 'leaf',
                'class' => $data[0]['hasil'],
                'count' => count($data),
                'confidence' => 1.0
            ];
        }
        
        // Base case 2: No more attributes
        if (empty($attributes)) {
            return [
                'type' => 'leaf',
                'class' => $this->getMajorityClass($data),
                'count' => count($data),
                'confidence' => 0.8
            ];
        }
        
        // Recursive case: Find best attribute and split
        $best = $this->findBestAttribute($data, $attributes);
        
        if ($best['attribute'] === null) {
            return [
                'type' => 'leaf',
                'class' => $this->getMajorityClass($data),
                'count' => count($data),
                'confidence' => 0.8
            ];
        }
        
        $attribute = $best['attribute'];
        $ig = $best['ig'];
        
        // Split data
        $subsets = [];
        foreach ($data as $item) {
            $value = $item[$attribute];
            if (!isset($subsets[$value])) {
                $subsets[$value] = [];
            }
            $subsets[$value][] = $item;
        }
        
        // Remove current attribute from future splits
        $remainingAttributes = array_diff($attributes, [$attribute]);
        
        // Build subtrees
        $children = [];
        foreach ($subsets as $value => $subset) {
            $children[$value] = $this->buildTree($subset, $remainingAttributes, $depth + 1);
        }
        
        return [
            'type' => 'node',
            'attribute' => $attribute,
            'ig' => $ig,
            'children' => $children,
            'count' => count($data)
        ];
    }
    
    /**
     * Predict using built tree
     */
    public function predict($tree, $input)
    {
        // If leaf node
        if ($tree['type'] === 'leaf') {
            return [
                'hasil' => $tree['class'],
                'confidence' => $tree['confidence'],
                'path' => []
            ];
        }
        
        // If decision node
        $attribute = $tree['attribute'];
        $value = $input[$attribute];
        
        // Check if value exists in children
        if (!isset($tree['children'][$value])) {
            // If value not found, return majority
            return [
                'hasil' => 'Unknown',
                'confidence' => 0.5,
                'path' => [$attribute . ' = ' . $value . ' (Value not in training data)']
            ];
        }
        
        $childTree = $tree['children'][$value];
        $prediction = $this->predict($childTree, $input);
        
        // Add current path
        array_unshift($prediction['path'], $attribute . ' = ' . $value);
        
        return $prediction;
    }
    
    /**
     * Get training data
     */
    public function getTrainingData()
    {
        return EventData::all()->map(function ($item) {
            return [
                'peserta' => $item->peserta,
                'budget' => $item->budget,
                'speaker' => $item->speaker,
                'topik' => $item->topik,
                'hasil' => $item->hasil
            ];
        })->toArray();
    }
    
    /**
     * Build and cache tree
     */
    public function getDecisionTree()
    {
        $data = $this->getTrainingData();
        $attributes = ['peserta', 'budget', 'speaker', 'topik'];
        
        return $this->buildTree($data, $attributes);
    }
    
    /**
     * Get tree statistics
     */
    public function getTreeStats()
    {
        $data = $this->getTrainingData();
        $total = count($data);
        
        $sukses = 0;
        $gagal = 0;
        
        foreach ($data as $item) {
            if ($item['hasil'] === 'Sukses') {
                $sukses++;
            } else {
                $gagal++;
            }
        }
        
        return [
            'total' => $total,
            'sukses' => $sukses,
            'gagal' => $gagal,
            'entropy_awal' => $this->calculateEntropy($data)
        ];
    }
}