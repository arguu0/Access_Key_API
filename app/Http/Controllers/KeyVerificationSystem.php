<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Key;
use Illuminate\Support\Str;

class KeyVerificationSystem extends Controller
{
    public function generate_key() {
        $key = Str::random(64);
    
        Key::create([ 'key'=> $key ]);

        return response()->json([ "key"=> $key ]);
    }

    public function verify_key(Request $request) 
    {
        $key = $request->input('key');
        try {
            $check_exist = Key::where('key', $key)->firstOrFail()->key;
            if ($check_exist) {
                $customToken = Str::random(64);
                dump($customToken);
                // return response()->json(['message' => 'Success'], 200);
            }
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid Key'], 404);
        };
    }
}
