<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    protected $fillable = [
        'num_area',
        'consecutive',
        'date',
        'addressee',
        'description',
        'area',
        'status'
    ];
}
