<?php

namespace App\Models;

use App\Models\Abonne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Abonnement extends Model
{
    use HasFactory;
    protected $fillable = [
        'montant',
        'abonnes_id',
        'status',
    ];
    public function abonne()
    {
        return $this->belongsTo(Abonne::class);
    }
}
