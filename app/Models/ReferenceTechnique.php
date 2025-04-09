<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferenceTechnique extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'reference_technique',
        'motorisation',
        'boite_vitesse',
        'puissance_thermique',
        'puissance_fiscale',
        'cylindree',
        'modele_id',
    ];
    public function modele(): BelongsTo
    {
        return $this->belongsTo(ModeleVoiture::class);
    }
}