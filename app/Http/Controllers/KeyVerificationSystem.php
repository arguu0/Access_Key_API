<?php

namespace App\Http\Controllers;

use App\Models\AuthToken;
use Illuminate\Http\Request;
use App\Models\Key;
use DateTime;
use Illuminate\Support\Str;

class KeyVerificationSystem extends Controller
{
    public function generate_key() {
        $key = Str::random(32);  // generate random 32 characters

        $curr_time = new DateTime('now');   // get current time

        $curr_time->modify('+1 hour');    // add 1 hour
    
        Key::create([ 'key'=> hash('sha256', $key, 64),   // insert value to table + hash the value to 64bit binary
                      'expires_at'=> $curr_time->format('Y-m-d H:i:s') ]);   // insert expires time in DB format (2026-12-20 13:05:39)

        return response()->json([ "KEY"=> $key ]);  
    }

    public function verify_key(Request $request) 
    {
        $input_key = $request->input('key');  // get the input key send from frontend

        // find the first key that match with the above input key | *saved key was hashed*
        $Key = Key::where('key', hash('sha256', $input_key, 64));  

        if (!$input_key || !$Key->exists()) {   // check if input key was empty or key does not exist
            return response()->json([ 'msg'=> "invalid KEY" ], 404);
        }
        
        $key_info = $Key->firstOrFail();  // get exp time from key

        $curr_time = new DateTime('now');   // get current time
    
        if ($curr_time->format('Y-m-d H:i:s') < $key_info->expires_at) {    // if key exp_time hasnt reached
            
            $AuthToken = Str::random(64);  // generate random 64 characters | will be use as bearer token for authorizing protected endpoints
        
            $curr_time->modify('+15 minutes');    // add 15 minutes

            if (AuthToken::where('id', $key_info->id)->exists()) {   // if bearer token exist, override it by updating
                $key_info->token()->update([ 'token' => hash('sha256', $AuthToken, 64),
                                             'expires_at' => $curr_time->format('Y-m-d H:i:s') ]);
            } else {

            // insert new value to token table
            $key_info->token()->create([ 'token'=> hash('sha256', $AuthToken, 64),   // saved as hashed
                                         'expires_at'=> $curr_time->format('Y-m-d H:i:s') ]);   
            }
            // sending json response
            return response()->json(['msg' => 'Success',
                                    'Remember Your User ID'=> $key_info->id,
                                    'Auth_token [use this in authorization]'=> $AuthToken], 200);   // intentionally sending this because testing on postman

        } else {   
            // if key exp time reached or greater
            return response()->json([ 'msg'=> "KEY Expired. Create a new key." ], 404);
        }
    }

    public function ViewProtectedRoute (Request $request)
    {
        $token = $request->attributes->get('token');   // get the attribute we set in the middleware
       
        $curr_time = new DateTime('now');
        
        if ($curr_time->format('Y-m-d H:i:s') > $token->key->expires_at) {

            return response()->json([ 'msg'=> "Unauthorised Access. Login using the key again"], 401);

        }

        $interval = $curr_time->diff(new DateTime($token->key->expires_at));   // time difference between *key* exp time and curr time

        return response()->json([ 'KEY ID'=> $token->key_id,       // interval is in object state so had to format in order to print values
                                  'Key Expires in'=>$interval->format('%i min %s sec')], 200);   
    }
}
