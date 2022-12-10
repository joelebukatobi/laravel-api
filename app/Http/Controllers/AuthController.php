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
            'username' => 'required|unique:users|string', 
            'email' => 'required|unique:users|string', 
            'password' => 'required|string',
            'role_id'=> 'required'
        ], 
        [  
            'first_name' => 'Please enter your first name',
            'last_name' => 'Please enter your last name',
            'username.required' => 'Please enter your your preferred username', 
            'username.unique' => 'This username has been taken', 
            'email.required' => 'Please enter your email', 
            'email.unique' => 'This email has already been used', 
            'password' => 'Please enter you password',
            'role_id' => "Please enter user's role",
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
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