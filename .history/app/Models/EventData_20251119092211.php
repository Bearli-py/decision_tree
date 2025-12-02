<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventData extends Model
{
    protected $table = 'event_data';
    
    protected $fillable = [
        'peserta',
        'budget',
        'speaker',
        'topik',
        'hasil'
    ];
}