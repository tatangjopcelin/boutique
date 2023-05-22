<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Recette;
use App\Models\Categorie;
use Illuminate\Http\Request;

class RecetteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mieu_vote = Recette::max('vote')->get(3);
        $recettes = Recette::paginate(3);
        $categories = Categorie::all();
        
        if($request->search){

            $recettes=Recette::where('nom','like','%'.$request->search.'%')
            ->orWhere('description','like','%'.$request->search.'%')
            ->where('publie','==',1)
            ->paginate(3)
            ->withQueryString();

        }elseif ($request->categorie) {

            $recettes=Categorie::where('nom',$request->categorie)
            ->firstOrFail()->recettes()
            ->paginate(3)
            ->withQueryString();
            
        }
        if ($request->ajax() ) {

            $view = view('layout.Recettes.data', compact('Recettes'))->render();
            return $view;
        
    }
    
      if (
        $recettes->count() > 0 &&
        $categories->count() > 0 && 
        $mieu_vote->count() >0
      ) {
        return response()->json([
             'status'     => 200, //tous c'est bien passées
             'recettes'   => $recettes,
             'abonnes'    => $abonnes,
             'mieu_vote'  => $mieu_vote
        ]);
      }
      if (
        $recettes->count() == 0 &&
        $categories->count() == 0 && 
        $mieu_vote->count() ==0
      ) {
        return response()->json([
             'status'     => 404, //aucun enregistrement pour le moment
             'message'   => "Aucun enregistrement"
        ]);
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recette  $recette
     * @return \Illuminate\Http\Response
     */
    public function show(Recette $recette)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recette  $recette
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recette $recette)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recette  $recette
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recette $recette)
    {
        //
    }
}
