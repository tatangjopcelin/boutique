<?php

namespace App\Http\Controllers;

use App\Models\Abonne;
use App\Models\Abonnement;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    public function getTotalPay() {
        
    }
    public function blockUser(Abonne $abonne) {
        $a = Abonne::fiblocknd($abonne);
        if (is_null($a)) {
            return response()->json(['msg' => 'Cet abonne n\'existe plus.'],401);
        }
        $abn = Abonnement::where('abonnes_id',$a->id);
        $abn->update([
             'status' => 'bloqué'
        ]);

        return response()->json(['msg' => 'Bloqué'],201);
    }
}
