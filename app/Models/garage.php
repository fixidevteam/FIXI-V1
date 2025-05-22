<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class garage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'ref',
        'photo',
        'ville',
        'quartier',
        'localisation',
        'user_id',
        'virtualGarage',
        'services',
        'domaines',
        'confirmation',
        'presentation',
        'telephone',
        'fixe',
        'whatsapp',
        'instagram',
        'facebook',
        'tiktok',
        'linkedin',
        'latitude',
        'longitude',
    ];
    protected $casts = [
        'services' => 'array',
        'domaines' => 'array',
        'domaines' => 'array',
    ];
    public function mechanics(): HasMany
    {
        return $this->hasMany(Mechanic::class);
    }
      public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    // Garage.php
    public function photos(): HasMany
    {
        return $this->hasMany(PhotoGarage::class);
    }
}