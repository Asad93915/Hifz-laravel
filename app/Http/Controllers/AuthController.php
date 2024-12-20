<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login the user and return an API token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
       
        // Validate incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
      
        // Log the incoming data (useful for debugging)
        \Log::info('Login attempt', ['email' => $request->email]);

        // Attempt to authenticate the user with the provided credentials
        $user = User::where('email', $request->email)->first();

        // dd(Hash::check($request->password, $user->password));
     
        // Check if the user exists and if the password matches
        // if (!$user || !Hash::check($request->password, $user->password))
        if (!$user ) {
            // Log the failed attempt
            \Log::info('Failed login attempt', ['email' => $request->email]);

            // Return a response indicating incorrect credentials
            return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
        }
   
        // Generate the authentication token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Log the successful login (optional for debugging)
        \Log::info('Successful login', ['email' => $user->email]);

        // Return the response with the token and user details
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }
}
