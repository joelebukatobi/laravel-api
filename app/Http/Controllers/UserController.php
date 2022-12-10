<?php

namespace App\Http\Controllers;


use Storage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        //
        $this->authorize('create-delete-users');
        $users = User::with(['role'])->orderBy('created_at', 'asc')->get();
        $response = [
            'success' => true,
            'users' => $users,
        ];
        
        return response ($response, 200);
    }

    public function me(Request $request)
    {
        $user =  auth('sanctum')->user()->load('role');
        $response = [
            'success' => true,
            'user' => $user, 
        ];

        return response($response, 200);
    }

    public function show(Request $request, $username)
    {
        $user = User::with(['role'])->orderBy('created_at', 'asc')->where(['username' => $username])->firstOrFail();
        $response = [
            'success' => true,
            'user' => $user, 
        ];

        return response($response, 200);
    }

    public function register( Request $request) 
    { 
        $this->authorize('create-delete-users');
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

        $filename = "";
        if ($request->file('image')) {
            $filename = $request->file('image')->store('images/thumbnail', 'public');
        } else {
            $filename = "null";
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'image' => $filename,
        ]);

        $response = [
            'message' => 'User created successfully',
            'user' => $user, 
        ];

        return response($response, 201);
    }

    public function update(Request $request, $username)
    {
        //
        $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|string', 
                'password' => 'required|string',
            ], 
            [  
                'first_name' => 'Please enter your first name',
                'last_name' => 'Please enter your last name',
                'username.required' => 'Please enter your your preferred username', 
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
            if (Storage::disk('public')->exists($user->image || is_null($user->image))) {
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

    public function destroy($username)
    {
        //
        $this->authorize('create-delete-users');
        $user = User::where(['username' => $username])->firstOrFail()->delete();
        $response = [
            'success' => true,
            'message' => 'User deleted successfully',
        ];

        return response($response, 200);
    }
}