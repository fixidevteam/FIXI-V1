<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_full_name',
        'user_phone',
        'user_email',
        'garage_ref',
        'appointment_day',
        'appointment_time',
        'status',
    ];
}