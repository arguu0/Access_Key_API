<?php

namespace App\Http\Middleware;

use App\Models\AuthToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BearerAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearer_token = $request->bearerToken();  // get the token from request header

        $token = AuthToken::where('token', hash('sha256', $bearer_token, 64 ));  // check whether the token is in DB
        
        if (!$bearer_token || !$token->exists()) {      // if bearer token is missing or wrong | exists() results in boolean

            return response()->json([ 'msg'=> "Invalid Access" ], 401);

        } else {

            $request->attributes->set('token', $token->firstOrFail());   // attaches a new attribute to next request -> controller

            return $next($request);
        }
    }
}
