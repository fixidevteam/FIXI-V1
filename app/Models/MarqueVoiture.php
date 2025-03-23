<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarqueVoiture extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'marque',
    ];
    public function modeles(): HasMany
    {
        return $this->hasMany(ModeleVoiture::class , 'marque_id');
    }
}