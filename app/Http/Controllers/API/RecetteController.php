<?php

namespace App\Http\Controllers\API;

use App\Models\Met;
use App\Models\User;
use App\Models\Photo;
use App\Models\Video;

use App\Models\Recette;
use App\Models\Categorie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ConvertVideoForStreaming;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\StoreVideoRequest;
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

    //  public function __contruct()
    //  {
    //       $this->middleware('api');
    //  }
    private $apiUrl = 'http://www.projet.univ/';

    public function index(Request $request)
    { 
     $recettes = Recette::all();
      $msg = 'Total recette ';
        if($request->met){

          $recettes = Recette::where('mets_id',$request->met)
          ->latest()
          ->get();
          $met = Met::find($request->met);

          $msg = 'totale recette pour le met '.$met->nom;
          

          
          if (is_null($recettes)) {

               return response()
               ->json(['msg' => 'Aucune recette pour le met '.Met::find($request->met)->name],201);
          }

        }
        if ($request->search) {

          $recettes = Recette::where('nom','LIKE','%'.$request->search.'%')

          ->orWhere('description','LIKE','%'.$request->search.'%')
          ->latest()
          ->get();

          $msg = 'Totale resultat pour '.$request->search;
        }
        if(is_null($recettes)){
          
          
          return response()
          ->json(['msg' => 'Aucune recette '.$request->search.' pour le moment'],201);

        }
        
      
      return response()->json([

           $msg => count($recettes),

           'recettes' => RecetteResource::collection($recettes),

      ],201);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(

      // StorePhotoRequest $vp,
      // StoreVideoRequest $vq,
      Request $request,

   )
    {

      $input = $request->all();

      $validator = Validator::make($input,[

               'nom'  => 'required|string|min:10',

               'description' => 'required',

               'met_id' =>'required',

               'budget'  => 'required|min:0',

               'duree' => 'required',

               'user_id' => 'required',

               'image'  => 'required|image',

               'video' => 'required|file|mimetypes:video/mp4,video/mpeg,video/x-matroska',

              ],
      );

      if ($validator->fails()) {

        return response()->json( $validator->errors(),401);

      }

      $user = User::find($request->user_id);

      $met = Met::find($request->met_id);

      if(is_null($user)){

        return response()->json('Utilisateur inconnu',201);

      }
      if(is_null($met)){

        return response()->json('Veuillez sélectioner un met',201);

      }
      if ($user->admin == 0) {

        return response()->json('Cette utilisateur n\'est pas autorisé a créer les recettes',201);
      
      }

       $recette = Recette::create([

            'users_id' => $request->user_id,

            'nom' => $request->nom,

            'description' => $request->description,
            
            'budget'  => $request->budget,

            'duree' => $request->duree,

            'mets_id' => $request->met_id,

       ]);

       $id_recette = $recette['id'];

       
      // get image input file and store
      $image=$request->file("image")
      ->store('photo-recette','public');

      $pathImg = $this->apiUrl.'storage/'.$image;

      // get video input file and store
      $video=$request->file("video")
      ->store('videos-recettes','public');

      $pathVideo ='storage/'.$video;
      
        $photo = Photo::create([

            'photo_path'=>$pathImg,

            'recettes_id'=>$id_recette,

        ]);

        // video manipulation and storable path
        $video = Video::create([

          'recettes_id'    => $id_recette,

          'path'          => $pathVideo,
        ]);

      //for streamable video
      ConvertVideoForStreaming::dispatch($video);
      
      // after converting video for streaming, we need to update videos table
      // to add the streamable url

      // $streamUrl = Storage::disk('streamable_videos','public')->url($video->id . '.m3u8');

      //   $video->update([

      //       'streamable_url' => $this->apiUrl.''.$streamUrl,

      //   ]);

       return response()->json(

        new RecetteResource($recette), 
        201
      );

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recette  $recette
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recette = Recette::where('id',$id)
        ->firstOrFail();

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
     * return recipes of specifique meel
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recette  $recette
     * @return \Illuminate\Http\Response
     */
    function recettesMet($idMet){

      return response()->json(Met::find($idMet)->recettes()->get());
    
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

               'nom'  => 'required|string|min:10',

               'description' => 'required',

               'met_id' =>'required',

               'budget'  => 'required|min:0',

               'duree' => 'required',

               'user_id' => 'required',

              //  'image'  => 'required|image',

              //  'video' => 'required|file|mimetypes:video/mp4,video/mpeg,video/x-matroska',

              ],
      );

      if ($validator->fails()) {

        return response()->json( $validator->errors(),401);

      }

      $user = User::find($request->user_id);

      $met = Met::find($request->met_id);

      if(is_null($user)){

        return response()->json(['message'=>'Utilisateur inconnu'],201);

      }
      if(is_null($met)){

        return response()->json(['message'=>'Veuillez sélectioner un met'],201);

      }
      if ($user->admin == 0) {

        return response()->json(['message'=>'Cette utilisateur n\'est pas autorisé a créer les recettes'],201);
      
      }

      $recette->nom = $request->nom;
      
      $recette->users_id = $request->user_id;

      $recette->description = $request->description;
          
      $recette->budget  = $request->budget;

      $recette->duree =  $request->duree;

      $recette->mets_id = $request->met_id;

      $recette->update();

    //    $id_recette = $recette['id'];

       
    //   // get image input file and store
    //   $image=$request->file("image")
    //   ->store('photo-recette','public');

    //   $pathImg = $this->apiUrl.'storage/'.$image;

    //   // get video input file and store
    //   $video=$request->file("video")
    //   ->store('videos-recettes','public');

    //   $pathVideo ='storage/'.$video;
      
    //   $photo = Photo::where('mets_id',$id_recette);

    //   if (!is_null($photo)) {
    //     $photo->photo_path = $pathImg;
    //     $photo->save();
    //   }

    // // video manipulation and storable path
    // $video = Video::where('mets_id',$id_recette);

    // if (!is_null($video)) {
    //   $photo->photo_path = $pathImg;
    //   $video->save();
    // }
    //   //for streamable video
    //   ConvertVideoForStreaming::dispatch($video);

      return response()->json(

        [
          'message' => 'Recette modifiée avec success.'
        ],
        200

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
