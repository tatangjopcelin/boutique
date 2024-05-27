<?php

namespace App\Models;

use App\Models\Abonnement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Abonne extends Model
{
    protected $fillable = [
            'nom',
            'prenom',
            'email',
            'tel',
            'users_id',
            'pays',
    ];
    use HasFactory;
    public function abonnement()
    {
        return $this->hasOne(Abonnement::class);
    }
}
