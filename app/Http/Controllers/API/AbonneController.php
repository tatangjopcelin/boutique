<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Abonne;
use Illuminate\Http\Request;

class AbonneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abonnes = Abonne::all();
        return response()->json($abonnes,201);
    }
    public function getAbonneByPaiementType(String $tp)
    {
        $abonnes = Abonne::where('type_paiement',$tp);
        return response()->json($abonnes,201);
    }
    public function getAbonneBySexe(String $sx)

    {
        $abonnes = Abonne::where('sexe',$sx);
        return response()->json($abonnes,201);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
        'nom'=> 'required',
        'prenom'=> 'required',
        'email'=> 'required',
        'password'=> 'required',
        'sexe'=>'request',
        'photo_path'=>'required|image',
      ]);
     $image = $request->file('photo_path')
     ->store('storage/photo-abonnes','public');
      $path = 'storage/photo-abonnes'.$image;

        $abonne= Abonne:: create([
            'nom'=>$request->input('nom'),
            'prenom'=>$request->input('prenom'),
            'email'=>$request->input('email'),
            'password'=>$request->input('password'),
            'sexe'=>$request->input('sexe'),
            'region'=>$request->input('region'),
            'ville'=>$request->input('ville'),
            'code_postal'=>$request->input('code_postal'),
            'photo_path'=>$path,
            'type_paiement'=>$request->input('type_paiement')
        ]);
        return response()->json($abonne, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Abonne  $abonne
     * @return \Illuminate\Http\Response
     */
    public function show(Abonne $abonne)
    {
        $abn=Abonne::find($abonne->id())->firstOrFail();
        $msg = "Aucun abonne de ce type pour le momnent";

       if ($abn) {
           return response()->json($abn,201);
       }
       return response()->json($msg,404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Abonne  $abonne
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Abonne $abonne)
    {
        $request->validate([
            'nom'=> 'required',
            'prenom'=> 'required',
            'email'=> 'required',
            'password'=> 'required'
          ]);
          $image = $request->file('photo_path')
          ->store('storage/photo-abonnes','public');
           $path = 'storage/photo-abonnes'.$image;

        //rechercher si l'abonnne existe dans la base de donnees
        $abn = Abonne::find($abonne->id())->firstOrFail();
        if ($abn) {
            $abonne->nom = $request->input('nom');
            $abonne->prenom = $request->input('prenom');
            $abonne->email = $request->input('email');
            $abonne->password = $request->input('password');
            $aboone->region = $request->input('region');
            $abonne->ville = $request->input('ville');
            $abonne->code_postal = $request->input('code_postal');
            $abonne->type_paiement = $request->input('type_paiement');
            $abonne->sexe = $request->input('sexe');
            $abonne->photo_path = $path;
            $abonne->update();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Abonne  $abonne
     * @return \Illuminate\Http\Response
     */
    public function destroy(Abonne $abonne)
    {
        $abonne->delete();
        return response()->json("Abonne suprime avec succes",201);
    }
}
