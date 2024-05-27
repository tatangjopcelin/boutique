<?php

namespace App\Models;

use App\Models\Recette;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'image',
    ];

    public function recette()
    {
        return $this->belongsTo(Recette::class);
    }
}
