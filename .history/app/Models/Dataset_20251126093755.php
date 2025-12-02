<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    protected $fillable = [
        'file_name',
        'file_path',
        'data_json',
        'total_records',
        'yes_count',
        'no_count',
        'calculation_json',
        'tree_json'
    ];

    protected $casts = [
        'data_json' => 'array',
        'calculation_json' => 'array',
        'tree_json' => 'array'
    ];
}