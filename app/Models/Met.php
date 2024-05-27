<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Met extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nom',
        'description',
        'image',
    ];
    protected $table = 'mets';

    public function recettes()
    {
        return $this->hasMany(Recette::class);
    }
}
