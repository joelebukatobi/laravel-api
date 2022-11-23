<?php

namespace App\Http\Controllers;


use Storage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::orderBy('created_at', 'asc')->get();
        $response = [
            'success' => true,
            'users' => $users,
        ];
        
        return response ($response, 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user =  auth('sanctum')->user();
        $response = [
            'success' => true,
            'user' => $user, 
        ];

        return response($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $username)
    {
        //

        $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'username' => 'required|unique:users|string', 
                'email' => 'required|unique:users|string', 
                'password' => 'required|string',
            ], 
            [  
                'first_name' => 'Please enter your first name',
                'last_name' => 'Please enter your last name',
                'username.required' => 'Please enter your your preferred username', 
                'username.unique' => 'This username has been taken', 
                'email.required' => 'Please enter your email', 
                'email.unique' => 'This email has already been used', 
                'password' => 'Please enter you password',
            ]
        );

        $user = User::where(['username' => $username])->firstOrFail();
        $edit = $request->all();

        $edit['password'] = bcrypt($request->password);

        $filename = "";
        if ($request->file('new_image')) {
            if (Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }     
            $filename = $request->file('new_image')->store('images/users', 'public');
            $edit['image'] = $filename;
        } else {
            $filename = $user->image;
        };
        
        $user->update($edit);

        $response = [
            'success' => true,
            'message' => 'Post updated successfully',
            'user' => $user, 
        ];

        return response($response, 200);
    }
}