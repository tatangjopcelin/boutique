<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller

{

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

    public function sendResponse($result, $message)
    {

    	$response = [

            'success' => true,

            'recettes'    => $result,

            'message' => $message,

        ];

        return response()->json($response, 200);

    }
    
    /**

     * return error response.

     *

     * @return \Illuminate\Http\Response

     */

    public function sendErrors($error, $errorMessages = [], $code = 404)
    {

    	$response = [

            'success' => false,

            'message' => $error,
            
            'status' =>$code
        ];



        if(!empty($errorMessages)){

            $response['erros'] = $errorMessages;

        }



        return response()->json($response);

    }

}