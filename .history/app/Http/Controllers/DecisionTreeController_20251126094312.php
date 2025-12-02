<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
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
     * Landing page
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Handle file upload
     */
    public function upload(Request $request)
    {
        try {
            // Validate file
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls'
            ]);

            // Parse Excel
            $data = $this->excelParser->parseFile($request->file('file'));

            if (empty($data)) {
                return back()->withErrors(['file' => 'File Excel kosong!']);
            }

            // Calculate metrics
            $metrics = $this->calculationService->calculateAllMetrics($data);

            // Build tree
            $tree = $this->treeService->buildTree($data);

            // Save to database
            $dataset = Dataset::create([
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_path' => $request->file('file')->store('uploads'),
                'data_json' => json_encode($data),
                'total_records' => count($data),
                'yes_count' => $metrics['yes_count'],
                'no_count' => $metrics['no_count'],
                'calculation_json' => json_encode($metrics),
                'tree_json' => json_encode($tree)
            ]);

            return redirect()->route('results', ['dataset' => $dataset->id]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display calculation results & tree
     */
    public function results($datasetId)
    {
        $dataset = Dataset::findOrFail($datasetId);

        return view('results', [
            'dataset' => $dataset,
            'metrics' => $dataset->calculation_json,
            'tree' => $dataset->tree_json,
            'data' => json_decode($dataset->data_json, true)
        ]);
    }

    /**
     * API endpoint untuk D3.js (return tree JSON)
     */
    public function getTreeJson($datasetId)
    {
        $dataset = Dataset::findOrFail($datasetId);

        return response()->json($dataset->tree_json);
    }
}