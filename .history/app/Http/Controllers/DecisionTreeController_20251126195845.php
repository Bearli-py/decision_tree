<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DecisionTreeController extends Controller
{
/**
  Landing page - Show upload form
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
        return "Upload endpoint";
    }

    /**
     * Display calculation results & tree
     */
    public function results($datasetId)
    {
        return "Results endpoint";
    }

    /**
     * API endpoint untuk D3.js
     */
    public function getTreeJson($datasetId)
    {
        return response()->json(['test' => 'data']);
    }
}