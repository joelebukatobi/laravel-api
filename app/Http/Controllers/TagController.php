<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tag = Tag::orderBy('id', 'asc')->get();
        $response = [
            'success' => true,
            'tags' => $tag,
        ];
        
        return response ($response, 200);
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
                'name' => 'required|string|unique:tags',
            ], 
            [  
                'name.required' => 'Please enter a tag name',
                'name.unique' => 'Sorry, this name has already been used',
            ]
        ); 
        
        $tag = Tag::create([
            'name' => $request->name,
        ]);

        $response = [
            'success' => true,
            'message' => 'Tag added successfully',
            'tag' => $tag, 
            
        ];

        return response($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
        $tag = Tag::where(['slug' => $slug])->firstOrFail();
        $response = [
            'success' => true,
            'tag' => $tag, 
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
    public function update(Request $request, $slug)
    {
        //
        $tag = Tag::where(['slug' => $slug])->firstOrFail();
        $tag->update($request->validate([
                'name' => 'required|string|unique:tags',
            ], 
            [  
                'name.required' => 'Please enter a tag name',
                'name.unique' => 'Sorry, this name has already been used',
            ]
        )); 
        $response = [
            'success' => true,
            'message' => 'Tag updated successfully',
            'tag' => $tag, 
        ];

        return response($response, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        //
        $tag = Tag::where(['slug' => $slug])->firstOrFail()->delete();
        $response = [
            'success' => true,
            'message' => 'Tag deleted successfully',
        ];

        return response($response, 200);
    }


        /**
     * Search for a name
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        //
        // return Product::where('name', 'like', '%'.$name.'%') -> get();
    }
}