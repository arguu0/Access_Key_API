<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Key;
use Illuminate\Support\Str;

class KeyVerificationSystem extends Controller
{
    public function generate_key() {
        $key = Str::random(32);  // generate random 32 characters
    
        Key::create([ 'key'=> hash('sha256', $key, 64) ]);    // insert value to table + hash the value to 64bit binary

        return response()->json([ "key"=> $key ]);  
    }

    public function verify_key(Request $request) 
    {
        $key = $request->input('key');  // get the input key send from frontend

        try {
            // find the first key that match with the above input key | *saved key was hashed*
            $check_exist = Key::where('key', hash('sha256', $key, 64))->firstOrFail();

            $AuthToken = Str::random(64);  // generate random 64 characters | will be use as bearer token for authorizing protected endpoints
            
            // insert value to table that has relation with "key" table
            $check_exist->token()->create([ 'token'=> hash('sha256', $AuthToken, 64) ]);   // saved as hashed

            return response()->json(['message' => 'Success',
                                    "Remember Your User ID"=> $check_exist->id,
                                    'Auth_token | Copy this'=> $AuthToken], 200);   // intentionally sending this because testing on postman

        } catch (\Throwable $e) {   // \Throwable means catch any error, so i dont have to name specific ErrorName

            return response()->json(['message' => 'Invalid Key'], 404);
        };
    }

    public function ViewProtectedRoute (Request $request) 
    {
        $token = $request->attributes->get('token');   // get the attribute we set in the middleware

        return response()->json([ 'KEY ID'=> $token->key_id], 200);  
    }
}
