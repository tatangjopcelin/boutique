<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
 
             'name' => ['required', 'string', 'max:255'],
 
             'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
 
             'password' => ['required', 'confirmed', Rules\Password::defaults()],
 
             'c_password' => 'required|same:password',
 
         ]);
 
    
 
         if($validator->fails()){
 
             return $this->sendError('Validation Error.', $validator->errors());       
 
         }
 
    
 
         $input = $request->all();
 
         $input['password'] = Hash::make($input['password']);
 
         $user = User::create($input);
 
         $success['token'] =  $user->createToken('recette_token')->plainTextToken;
 
         $success['name'] =  $user->name;
 
    
 
         return $this->sendResponse($success, 'User register successfully.');
 
     }
 
    
 
     /**
 
      * Login api
 
      *
 
      * @return \Illuminate\Http\Response
 
      */
 
     public function login(Request $request)
 
     {
 
         if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
 
             $user = Auth::user(); 
 
             $success['token'] =  $user->createToken('recette_token')->plainTextToken; 
 
             $success['name'] =  $user->name;
 
    
 
             return $this->sendResponse($success, 'User login successfully.');
 
         } 
 
         else{ 
 
             return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
 
         } 
 
     }
}
