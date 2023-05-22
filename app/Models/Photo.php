<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'photo_path',
        'recettes_id'
    ];
    use HasFactory;

    public function recette()
    {
        return $this->belongsTo(Recette::class);
    }
}
