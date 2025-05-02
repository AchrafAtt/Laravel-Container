<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class AutController extends Controller
{

    /**
     * Log in user via email and password.
     * 
     * 
     */


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $user = User::firstWhere('email',$request->email);

        // Generate a token for the user
        $token =  $user->createToken('API token for'.$user->email,['*'],now()->addMonth())->plainTextToken; 




        return response()->json(['message' => 'Login successful',
        'data' => [
            'user' => $user,
            'token' => $token,
        ]], 200);
    }
}
