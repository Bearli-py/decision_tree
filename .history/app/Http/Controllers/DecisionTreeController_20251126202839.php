<?php

namespace App\Http\Controllers;

use App\Services\ExcelParserService;
use App\Services\CalculationService;
use Illuminate\Http\Request;

class DecisionTreeController extends Controller
{
    protected $excelParser;
    protected $calculationService;

    public function __construct(ExcelParserService $excelParser, CalculationService $calculationService)
    {
        $this->excelParser = $excelParser;
        $this->calculationService = $calculationService;
    }

    /**
     * Home page
     */
    public function index()
    {
        $token = csrf_token();
        
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Decision Tree</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-5">
                <h1>Upload Dataset Excel</h1>
                <p>Upload file Excel (.xlsx) untuk membuat Decision Tree dengan metode ID3</p>
                
                <form action="/upload" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="$token">
                    <input type="file" name="file" accept=".xlsx,.xls" required>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
                
                <hr>
                <p><strong>Format file Excel:</strong></p>
                <p>Kolom: No, Peserta, Budget, Speaker, Topik, Play</p>
                <p>Peserta: "< 50" atau "≥ 50"<br>
                Budget: "Rendah" atau "Tinggi"<br>
                Speaker: "Biasa" atau "Expert"<br>
                Topik: "Trending" atau "Niche"<br>
                Play: "yes" atau "no"</p>
            </div>
        </body>
        </html>
        HTML;
    }

    /**
     * Handle upload & process
     */
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls'
            ]);

            // Parse Excel
            $data = $this->excelParser->parseFile($request->file('file'));

            // Calculate metrics
            $metrics = $this->calculationService->calculateAllMetrics($data);

            // Store ke session untuk display
            session([
                'dataset' => $data,
                'metrics' => $metrics,
                'file_name' => $request->file('file')->getClientOriginalName()
            ]);

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
        $fileName = session('file_name');

        return view('results', compact('data', 'metrics', 'fileName'));
    }
}