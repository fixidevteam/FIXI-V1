<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModeleVoiture extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'modele',
        'marque_id',
    ];
    public function marque(): BelongsTo
    {
        return $this->belongsTo(MarqueVoiture::class);
    }
}