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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:users,username', 
            'email' => 'required|string|unique:users,email', 
            'password' => 'required|string|confirmed', 
        ],
        [  
            'first_name' => 'Please enter your first name',
            'last_name' => 'Please enter your last name',
            'username' => 'Please enter your your preferred username', 
            'email' => 'Please enter your email', 
            'password' => 'Please enter you password',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'image'
        ]);

        $response = [
            'message' => 'User created successfully',
            'user' => $user, 
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