// app/Http/Controllers/DecisionTreeController.php

public function upload(Request $request)
{
    try {
        $request->validate([
            'file'          => 'required|file|mimes:csv,txt',
            'target_column' => 'required|string',
        ]);

        // STEP 1: Parse CSV -> data tabular generik
        $data = $this->excelParser->parseFile($request->file('file'));

        $targetColumn = $request->input('target_column');

        // STEP 2: Hitung metrik dinamis
        $metrics = $this->calculationService->calculateAllMetrics($data, $targetColumn);

        // STEP 3: Bangun tree dinamis
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
