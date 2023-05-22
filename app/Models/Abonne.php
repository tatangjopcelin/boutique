<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonne extends Model
{
    protected $fillable = [
            'nom',
            'prenom',
            'email',
            'password',
            'region',
            'ville',
            'photo_path',
            'type_paiement'
    ];
    use HasFactory;
}
