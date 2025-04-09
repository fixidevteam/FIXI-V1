<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoGarage extends Model
{
    use HasFactory;
    protected $fillable = [
        'photo',
        'garage_id'
    ];
    public function garage()
    {
        return $this->belongsTo(Garage::class);
    }
}
