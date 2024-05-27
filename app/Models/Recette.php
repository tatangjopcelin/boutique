<?php

namespace App\Models;

use App\Models\Met;
use App\Models\User;
use App\Models\Photo;
use App\Models\Video;
use App\Models\Etappe;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recette extends Model
{
    protected $fillable = [
            'nom',
            'description',
            'users_id',
            'mets_id',
            'budget',
            'duree',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function met()
    {
        return $this->belongsTo(Met::class);
    }
}
