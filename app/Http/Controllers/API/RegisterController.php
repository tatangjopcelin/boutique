<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;


class RegisterController extends BaseController
{
     /**

     * Register api

     *

     * @return \Illuminate\Http\Response

     */

     public function register(Request $request)

     {
 
         $validator = Validator::make($request->all(), [
 
             'name' => ['required', 'string','min:3','max:100'],
 
             'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
 
             'password' => ['required','min:4'],
 
             'c_password' => 'required|same:password',
 
         ]);
 
    
 
         if($validator->fails()){
 
             return response()->json($validator->errors(),401);       
 
         }
 
    
 
         $input = $request->all();
 
         $input['password'] = bcrypt($input['password']);
 
         $user = User::create($input);
 
         $token =  $user->createToken('recette_token')->accessToken;
 
         return response()->json([

            'message' =>'Vous ete inscript',
            'token'   =>$token,
            'status'  =>201

        ],201);
        
        $this->login($request);
 
     }
 
    
 
     /**
 
      * Login api
 
      *
 
      * @return \Illuminate\Http\Response
 
      */
 
     public function login(Request $request)
 
     {
 
         if(auth()->attempt(['email' => $request->email, 'password' => $request->password])){ 
 
             $this->user = auth()->user(); 
 
             $token =  $this->user->createToken('recette_token')->accessToken;
 
 
             return response()->json([
                'message' =>'Vous ete connecté',
                'user'    =>$this->user,
                'token'   =>$token,
                'status'  =>201
            ],201);
 
         } 
 
         else{ 
 
             return response()->json('  Non autorisé ',401);
 
         } 
 
     }

     public function userInfo(Request $request)
     {

       $user = User::where('email',$request->email)->firstOrFail();
       if(is_null($user)){
        return response()->json('Utilisateur nom reconnu', 201);
       }
       return response()->json(['utilisateur' => 
       new UserResource($user)], 201);
  
     }

     public function logout (Request $request) {

        $token = $request->user()->token();

        $token->revoke();

        $response = ['message' => 'You have been successfully logged out!'];

        return response($response, 200);
    }
     private $user;
}
