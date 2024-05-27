<?php

namespace App\Http\Controllers\API;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    private $apiUrl = 'http://www.projet.univ/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(),[

            'nom'=>'required|unique:ingredients,nom|max:55',

            'description' =>'required',

            'recettes_id' => 'required',

            'image'=>'required|image',
        ]);

        if($v->fails()){
            return response()->json(['errors' => $v->errors()],401);
        }


        // get image input file and store
      $image=$request->file("image")

      ->store('photo-ingredients','public');

      $pathImg =$this->apiUrl.'storage/photo-ingredients /'.$image;
        //creer un nouvel objet ingredient

        $ingredient = new Ingredient([

            'nom'=>$request->get('nom'),

            'description'=> $request->get('description'),
            
            'recettes_id' => $request->recettes_id,
            
            'image'=>$pathImg,
        ]);

        //enregistrer l'ingredient dans la base de donnees

        $ingredient->save();

        return response()->json('Ingredient crée avec succes',201);

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredient)
    {
        //
    }
}
