<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard()->attempt($credentials)) {
            $customer = Auth::user();

            $token = $customer->generateToken('CustomerToken');

            // Store the token in a variable or use it as needed
            $generatedToken = $token;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
