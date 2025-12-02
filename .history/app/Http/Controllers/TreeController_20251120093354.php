<?php

namespace App\Http\Controllers;

use App\Models\EventData;
use App\Services\DecisionTreeService;
use Illuminate\Http\Request;

class TreeController extends Controller
{
    protected $treeService;
    
    public function __construct(DecisionTreeService $treeService)
    {
        $this->treeService = $treeService;
    }
    
    /**
     * Halaman Home
     */
    public function home()
    {
        return view('home');
    }
    
    /**
     * Halaman Dataset
     */
    public function dataset()
    {
        $data = EventData::all();
        $stats = $this->treeService->getTreeStats();
        
        return view('dataset', [
            'data' => $data,
            'stats' => $stats
        ]);
    }
    
    /**
     * Halaman Tree Visualization
     */
    public function tree()
    {
        $tree = $this->treeService->getDecisionTree();
        $stats = $this->treeService->getTreeStats();
        
        return view('tree', [
            'tree' => $tree,
            'stats' => $stats
        ]);
    }
    
    /**
     * Halaman Prediction
     */
    public function predict()
    {
        return view('predict');
    }
    
    /**
     * Process Prediction (POST)
     */
    public function doPrediction(Request $request)
    {
        $tree = $this->treeService->getDecisionTree();
        
        $input = [
            'peserta' => $request->input('peserta'),
            'budget' => $request->input('budget'),
            'speaker' => $request->input('speaker'),
            'topik' => $request->input('topik')
        ];
        
        $prediction = $this->treeService->predict($tree, $input);
        
        return response()->json([
            'success' => true,
            'hasil' => $prediction['hasil'],
            'confidence' => round($prediction['confidence'] * 100) . '%',
            'path' => $prediction['path'],
            'input' => $input
        ]);
    }
    
    /**
     * Halaman Comparison
     */
    public function comparison()
    {
        return view('comparison');
    }
}