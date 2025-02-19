<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarageUnavailableTime extends Model
{
    use HasFactory;
    protected $fillable = [
        'garage_ref',
        'unavailable_day',
        'unavailable_from',
        'unavailable_to',
    ];
}