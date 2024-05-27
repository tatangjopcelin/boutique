<?php

namespace App\Http\Controllers\API;

use App\Models\Met;
use App\Models\Abonne;
use App\Models\Recette;
use App\Models\Abonnement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResources;

class DashboardController extends Controller
{
    public function dashboard(){

        $abs = Abonne::all();

        $tota_rem = Abonnement::all()->sum('montant');

        $m = Met::all();
        $r = Recette::all();
        
        return response()->json([

            'totat_met' => count($m),

            'totat_recette' => count($r),

            'banque' => $tota_rem ,

            'dashboard' => DashboardResources::collection($abs),
 
       ],200);

    }
}
