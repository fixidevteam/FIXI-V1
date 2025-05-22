<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'voiture_id',
        'garage_id',
    ];

    /**
     * Get the voiture (car) associated with the visit.
     */
    public function voiture()
    {
        return $this->belongsTo(Voiture::class);
    }

    /**
     * Get the garage associated with the visit.
     */
    public function garage()
    {
        return $this->belongsTo(Garage::class);
    }
}
