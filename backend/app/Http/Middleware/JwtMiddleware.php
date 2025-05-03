<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            // Check token presence and validity
            $user = JWTAuth::parseToken()->authenticate();
            
            // If user is not found after authentication
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
            
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
            
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is not provided'], 401);
        }

        return $next($request);
    }
}