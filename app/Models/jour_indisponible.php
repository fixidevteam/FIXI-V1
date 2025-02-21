<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jour_indisponible extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'garage_ref',
        'date',
    ];

}
