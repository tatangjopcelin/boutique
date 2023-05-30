<?php

namespace App\Http\Controllers\API;

use App\Models\Recette;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Recettes as RecetteResource;

class RecetteController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __contruct()
     {
          $this->middleware('auth:sanctum');
     }
    public function index()

    {
        $recettes = Recette::all();

        if(!is_null($recettes)){
          
          return $this->sendResponse(

            $recettes,

            'success'
          );

        }
        
        return $this->senErrors('Fail to fetch data');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

      $input = $request->all();

      $validator = Validator::make($input,[

               'nom'  => 'required',

               'desciption' => 'required'
              ],
      );

      if ($validator->fails()) {

        return $this->sendError('Validation Error.', $validator->errors());

      }

      $lien = Str::slug($nom,'-');

       $recette = Recette::create([

          'nom' => $request->input('nom'),

          'description' => $request->input('description'),
          'lien'  => $lien,
       ]);

       return $this->sendResponse(

        new RecetteResource($recette), 

        'Recipe created successfully.'

      );

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recette  $recette
     * @return \Illuminate\Http\Response
     */
    public function show($lien)
    {
        $recette = Recette::where('lien',$lien)->firstOrFail();

        if (!is_null($recette)) 
        {

          return $this->sendResponse(

            new RecetteResource($recette), 

            'success'

          );

        }

        return $this->sendError('Recipe not found.');
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

      $input = $request->all();

      $validator = Validator::make($input,[

               'nom'  => 'required',

               'desciption' => 'required'
              ],
      );

      if ($validator->fails()) {

        return $this->sendError('Validation Error.', $validator->errors());

      }

      $lien = Str::slug($nom,'-');

      $recette->nom = $input['nom'];

      $recette->description = $input['description'];

      $recette->lien = $lien;
      $recette->save();

      return $this->sendResponse(
        new RecetteResource($recette), 
        'Recipe updated successfully.'
      );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recette  $recette
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recette $recette)
    {
        $recette->delete();

        return $this->sendResponse([], 'Recipe deleted successfully.');

    }
}
