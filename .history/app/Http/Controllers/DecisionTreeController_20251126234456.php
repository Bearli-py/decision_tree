<?php

namespace App\Http\Controllers;

use App\Services\ExcelParserService;
use App\Services\CalculationService;
use App\Services\DecisionTreeService;
use Illuminate\Http\Request;

class DecisionTreeController extends Controller
{
    protected $excelParser;
    protected $calculationService;
    protected $treeService;

    public function __construct(
        ExcelParserService $excelParser, 
        CalculationService $calculationService,
        DecisionTreeService $treeService
    ) {
        $this->excelParser = $excelParser;
        $this->calculationService = $calculationService;
        $this->treeService = $treeService;
    }

    /**
     * Home page
     */
 

    /**
     * Handle upload & process
     */
public function upload(Request $request)
{
    try {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        // STEP 1: Parse CSV
        $data = $this->excelParser->parseFile($request->file('file'));

        // STEP 2: Calculate metrics
        $metrics = $this->calculationService->calculateAllMetrics($data);

        // STEP 3: Build tree
        $tree = $this->treeService->buildTree($data);

        // STEP 4: Store ke session
        session([
            'dataset' => $data,
            'metrics' => $metrics,
            'tree' => $tree,
            'file_name' => $request->file('file')->getClientOriginalName()
        ]);

        // STEP 5: Redirect ke results
        return redirect('/results');

    } catch (\Exception $e) {
        return redirect('/')->withErrors(['error' => $e->getMessage()]);
    }
}

    /**
     * Display results
     */
    public function results()
    {
        if (!session('dataset')) {
            return redirect('/');
        }

        $data = session('dataset');
        $metrics = session('metrics');
        $tree = session('tree');
        $fileName = session('file_name');

        return view('results', compact('data', 'metrics', 'tree', 'fileName'));
    }
}