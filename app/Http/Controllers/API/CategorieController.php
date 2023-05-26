<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Categorie::all();
        return response()->json($categories,201);

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

          ]);
          $categorie= Categorie::create([
            'nom'=>$request->input('nom'),

          ]);
          return response()->json($categorie, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function show(Categorie $categorie)
    {
        $cat=categorie::find($categorie->id())->firstOrFail();
        $msg = "Aucune categorie de cette type pour le momnent";

       if ($cat) {
           return response()->json($cat,201);
       }
       return response()->json($msg,404);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'nom'=> 'required',
        ]);
        // recherche si existe une categorie dans la base de donnee
        $cat = Categorie::find($categorie->id())->firstOrFail();
        if ($cat) {
            $categorie->nom = $request->input('nom');
            $categorie->update();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categorie $categorie)
    {
        $catgorie->delete();
        return response()->json("categorie suprime avec succes",201);
    }
}
