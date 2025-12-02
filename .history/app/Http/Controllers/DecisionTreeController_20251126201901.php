<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DecisionTreeController extends Controller
{
/**
 * Landing page - Show upload form
 */
public function index()
{
    return <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>Decision Tree</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <h1>Upload Dataset</h1>
            <p>Ini adalah halaman HARDCODED untuk test!</p>
            
            <form action="/upload" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" name="file" required>
                <button type="submit">Upload</button>
            </form>
        </div>
    </body>
    </html>
    HTML;
}