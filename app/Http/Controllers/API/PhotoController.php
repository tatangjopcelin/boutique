<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
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
    public function store(Request $request,Recette $recette)
    {
        $id_recette = $request->id();
        // //upload images
        $erro_gen = "Nous n'avons pas pu stocker votre image";
        $recette= Recette::find($id_recette)
        ->firstOrFail();
        if ($recette) {
            $image=$request->file("image")
            ->store('storage/photo-recette','public');
            $path ='storage/photo-recette/'.$image;
            $recette=Recette::create([
                'photo_path'=>$path,
                'recette_id'=>$id_recette,
            ]);
            return response()->json($recette, 201);
        }else{
            $error = "Aucune recette correspondant";
            return response()->json($error,404);
        }
       return response()->json($erro_gen,404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        $pho=Photo::find($photo-id())->firstOrfail();
        $msg= "aucune photo de ce gere pour le moment";
        if($pho){
            return response()->json($pho,201);
         }
         return response()->json($msg,404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
       $pho=Photo::find($photo->id())->firstOrFail();
       if($pho){

            $image=$request->file("image")
            ->store('storage/photo-recette','public');
             $path ='storage/photo-recette/'.$image;
             $request->validate([
                'photo_path'=>'required|image'
            ]);

            $photo->photo_path=$path;
            $photo->update();

            return response()->json("photo modifier avec succes",201);

           } else{
            
                 return response()->json("veillez vous rassurer si l'image éxiste dans la base de donnée ",404);
           }
           return response()->json("photo non modifier ",404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
       
    }
}
