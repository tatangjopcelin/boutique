<?php

namespace App\Http\Controllers\API;

use App\Models\Met;
use App\Models\User;
use App\Mail\NewsLettersMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Met as MetResource;

class MetController extends Controller
{

    private $apiUrl = 'www.projet.univ/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // $users = User::all();
        
        // foreach ($users as  $user) {
            
            
        //     Mail::to($user->email)
      
        //     ->send(new NewsLettersMail($user));

        // }
        
        $mets = Met::all();

        if(is_null($mets)){
            return response()->json('Aucun met enregisttré pour le moment',201);
        }
        if ($request->search) {
            $mets = Met::where('nom','LIKE','%'.$request->search.'%')
            ->orWhere('description','LIKE','%'.$request->search.'%')
            ->get();
        }
        return response()->json([

            'totale' => count($mets),

             'mets' => MetResource::collection($mets),

        ],201);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[

            'nom' => 'required|unique:mets',

            'description'  => 'required',

            'image'  => 'required|image|mimes:png,jpg,jpeg',
        ]);
       
        if ($validator->fails()) {

            return response()->json( $validator->errors(),401);
    
          }

        // get image input file and store
        
        $image = $request->file("image")

        ->store('mets','public');

        //Storage::store();

        $path =$this->apiUrl.'storage/'.$image;

        $met = Met::create([

            'nom' => $request->nom, 

            'description'  => $request->description,

            'image'  => $path,
        ]);

        return response()->json(['met' => new MetResource($met)],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Met  $met
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $met = Met::find($id);

        if (!is_null($met)) 
        {

          return response()->json(

            new MetResource($recette), 

            'success'

          );

        }
        return response()->json(['msg' => 'Met introuvable'],401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Met  $met
     * @return \Illuminate\Http\Response
     */
   

    public function update(Request $request, Met $met)
    {
        $validator=Validator::make($request->all(),[

            'nom' => 'required|unique:mets',

            'description'  => 'required',

        //    'image'  => 'required|image|mimes:png,jpg,jpeg',
        ]);
       
        if ($validator->fails()) {

            return response()->json( $validator->errors(),401);
    
          }

        // // get image input file and store
        // $image = $request->file("image")

        // ->store('mets','public');

        // $path ='storage/mets/'.$image;

        $met->nom = $request->nom;

        $met->description  = $request->description;

        // $met->image  = $path;

        $met->update();
        
        return response()->json(['msg' => 'mise a jour ok','met' => $met]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Met  $met
     * @return \Illuminate\Http\Response
     */
    public function destroy(Met $met)
    {
        $met->delete();

        return response()->json(['msg' => 'Met supprimé avec success'],201);
    }
}
