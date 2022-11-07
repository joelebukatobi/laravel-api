<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
   

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Subscription::all();
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
          //
        $request->validate([
            'email' => 'required|email',
        ], 
        [  
            'email.required' => 'Please enter your email',
        ]); 

        $email = Subscription::create([
            'email' => $request->email,
        ]);

        $response = [
            'success' => true,
            'message' => 'Subscription successful',
            'email' => $email, 
            
        ];

        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $subscription = Subscription::destroy($id);
        $response = [
            'success' => true,
            'message' => 'Category deleted successfully',
        ];

        return response($response, 200);
    }
}