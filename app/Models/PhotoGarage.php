<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoGarage extends Model
{
    use HasFactory;
    protected $fillable = [
        'photo',
        'garage_id'
    ];
    public function garage(): BelongsTo
    {
        return $this->belongsTo(Garage::class);
    }
}