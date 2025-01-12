<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $dates = [
        'converted_for_downloading_at',
        'converted_for_streaming_at',
    ];

    protected $guarded = [];
    
    use HasFactory;

    public function recette()
    {
        return $this->belongsTo(Recette::class);
    }
}
