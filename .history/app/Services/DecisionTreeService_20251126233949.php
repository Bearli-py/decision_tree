public function results()
{
    $data = session('dataset');
    $metrics = session('metrics');
    $tree = session('tree');
    $fileName = session('file_name', 'unknown.csv');

    if (!$data || !$metrics || !$tree) {
        return redirect('/');
    }

    return view('results', compact('data', 'metrics', 'tree', 'fileName'));
}