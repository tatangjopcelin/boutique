<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recette extends Model
{
    protected $fillable = [
            'nom',
            'description',
            'users_admin',
    ];

    use HasFactory;

    public function video()
    {
        return $this->hasOne(Video::class);
    }
    public function photo()
    {
        return $this->hasOne(Photo::class);
    }
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
    public function etappes()
    {
        return $this->hasMany(Etappe::class);
    }
}
