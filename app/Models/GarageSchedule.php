<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarageSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'garage_ref',
        'available_day',
        'available_from',
        'available_to',
    ];
}