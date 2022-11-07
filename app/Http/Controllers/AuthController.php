<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register( Request $request) { 
        $request->validate([
         'name' => 'required|string', 
         'email' => 'required|string|unique:users,email', 
         'password' => 'required|string|confirmed', 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('token')->plainTextToken;
        $response = [
            'user' => $user, 
            'message' => 'User created successfully',
            'token' => $token, 
        ];

        return response($response, 201);

    }

    public function login( Request $request) { 
        $request->validate([
         'email' => 'required|string', 
         'password' => 'required|string'
        ],
        [  
            'email.required' => 'Please enter your email',
            'password.required' => 'Please enter your password',
        ]
    );

        // Check User
        $user = User::where('email', $request->email)-> first();
        // Check Password
        if(!$user || !Hash::check($request->password, $user->password)) { 
            return response([
                'message' => 'Wrong credentials'
            ], 401);
        }

        $token = $user->createToken('token')->plainTextToken;
        $response = [
            'user' => $user, 
            'message' => 'User login successful',
            'token' => $token,
        ];

        return response($response, 201);

    }


    public function logout (Request $request) { 
        auth()->user()->tokens()->delete();
        return [ 
            'message' => 'User logged out'
        ];
    }
}