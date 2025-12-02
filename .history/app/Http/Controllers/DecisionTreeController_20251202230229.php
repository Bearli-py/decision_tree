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
     * Halaman home / upload
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Handle upload & process dataset
     */
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file'          => 'required|file|mimes:csv,txt',
                'target_column' => 'required|string',
            ]);

            // STEP 1: Parse CSV jadi array tabular
            $data = $this->excelParser->parseFile($request->file('file'));

            $targetColumn = $request->input('target_column');

            // STEP 2: Hitung metrics (entropy, gain, gain ratio untuk semua atribut)
            $metrics = $this->calculationService->calculateAllMetrics($data, $targetColumn);

            // STEP 3: Bangun decision tree
            $tree = $this->treeService->buildTree($data, $targetColumn);

            // STEP 4: Simpan ke session
            session([
                'dataset'       => $data,
                'metrics'       => $metrics,
                'tree'          => $tree,
                'file_name'     => $request->file('file')->getClientOriginalName(),
                'target_column' => $targetColumn,
            ]);

            return redirect('/results');

        } catch (\Throwable $e) {
            return redirect('/')->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Tampilkan hasil perhitungan & visualisasi tree
     */
    public function results()
    {
        if (!session('dataset')) {
            return redirect('/');
        }

        $data         = session('dataset');
        $metrics      = session('metrics');
        $tree         = session('tree');
        $fileName     = session('file_name');
        $targetColumn = session('target_column');

        return view('results', compact('data', 'metrics', 'tree', 'fileName', 'targetColumn'));
    }
}