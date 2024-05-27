<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Abonne;

use App\Mail\WelcomeMail;
use App\Models\Abonnement;
use Illuminate\Http\Request;
use App\Mail\AccountBloquedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;

class AbonneController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $an = Abonnement::where('status','Bloqué')->get();
        $abonnes = Abonnement::where('status','Bloqué')->get()->abonne();
        if (!is_null($abonnes)) {
          
            return response()->json(['abonnes' => $abonnes],201);
        }
        return response()->json('Aucun abonné pour le moment',201);
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
            'nom'=> 'required',
            'prenom' => 'required',
            'email' => 'nullable|unique:users',
            'tel' => 'required|unique:abonnes',
            'password'=> 'required',
            'somme' => 'required',
            'pays' => 'required|min:5|max:7',
        ]);
        if ($v->fails()) {
           return response()
           ->json(['erros' => $v->errors()],401);
        }
        if(is_null($request->tel) && is_null($request->email)){
            return response()
           ->json(['erros' => 'Veuillez remplir le champs Tel ou email'],401);
        }
        
        if((!is_null($request->tel) && !is_null($request->tel))
           || (!is_null($request->tel) && is_null($request->tel))
        ){

            $alea = rand(1000,9000);

            $email_def = 'RC-M'.$alea;

            $va = Validator::make([$email_def],[
      
                'email' => 'required|unique:users',
      
            ]);
            if ($va->fails()) {
                $alea = rand(9000,20000);
                $email_def = 'RC-M'.$alea;
            }


            
            
            
            
            $email = !is_null($request->email) ? $request->email : $email_def;
            
            // $basic  = new \Vonage\Client\Credentials\Basic(VONAGE_API_KEY, VONAGE_API_SECRET);
            // $client = new \Vonage\Client($basic);

            // $response = $client->sms()->send(
            //     new \Vonage\SMS\Message\SMS(TO_NUMBER, BRAND_NAME, 'A text message sent using the Nexmo SMS API')
            // );
            
            // $message = $response->current();
            
            // if ($message->getStatus() == 0) {
            //     echo "The message was sent successfully\n";
            // } else {
            //     echo "The message failed with status: " . $message->getStatus() . "\n";
            // }
            // $basic  = new \Nexmo\Client\Credentials\Basic('key', 'secret');

            // $client = new \Nexmo\Client($basic);
    
            // $message = $client->message()->send([
            //     'to' => $request->tel,
            //     'from' => 'Projet',
            //     'text' => 'Votre Login de connexion est '.$email,
            // ]);
            
        }
        
        $nc = $request->nom.' '.$request->prenom;
       
        // a chaque abonnement il y a creation d'un utilisateur
        $u = User::create([

            'name' => $nc,

            'email' => $email,

            'password' => bcrypt($request->password)

        ]);

        if(!is_null($request->email)){
            
            Mail::to($u->email)
      
            ->send(new WelcomeMail($u));
        }
        $email_2 = !is_null($request->email) ? $request->email : 'Pas d\'adresse électronique';
      
        // if ($request->hasFile()) {
            
        //     $url = 'http://wwww.projet.univ/';

        //     $image = $request->file('photo_path')
        //     ->store('photo-abonnes','public');
    
        //     $path = $url.'storage/photo-abonnes'.$image;//

        // }else{
        //     $path = $url.'storage/photo-abonnes/avatar.png';
        // }

        $abonne= Abonne:: create([
            'nom'=> $request->input('nom'),
            'prenom'=> $request->input('prenom'),
            'email'=> $email_2,
            'tel' => $request->tel,
            'users_id' => $u->id,
            'pays' => $request->pays,
        ]);

        //abonnement
        Abonnement::create([

            'montant' => $request->somme,

            'abonnes_id' => $abonne->id,

            'status' => 'Activé',
        ]);

        return response()->json([
            'abonnee' => $abonne,
            'Login'  =>$email,
            'msg'  => 'Abonnement reussi',
        ], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Abonne  $abonne
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $abn=Abonne::find($id)->firstOrFail();
        $msg = "Aucun abonne de ce type pour le momnent";

       if (!is_null($abn)) {
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
        return response()->json(["msg" => "Abonne suprime avec succes"],201);
    }
    public function bloquer($id)
    {
        $u = Abonne::find($id);

        if (is_null($u)) {
           return response()
           ->json([
            'message' => 'Cette abonné n\'existe pas',
           ],400);
        }

        $abn = Abonnement::find($u->id);
        $msg = '';
        if ($abn->status == 'Bloqué') {
            $abn->status = 'Activé';
            $msg .='Cet abonné a été débloqué';
        }else{
            $abn->status = 'Bloqué';
            $msg ='Cet abonné a été bloqué';
        }
        $abn->update();


        // Mail::to($u->email)
      
        // ->send(new AccountBloquedMail($u));

        return response()
        ->json([
            'message' => $msg,
        ],
        200);
    }
}
