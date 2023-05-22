<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etappe extends Model
{
    protected $dates = [
        'duree',
        'text',
        'recettes_id',
    ];

    use HasFactory;

    public function recette()
    {
        return $this->belongsTo(Recette::class);
    }
}
