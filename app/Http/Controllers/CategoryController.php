<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $category = Category::orderBy('id', 'asc')->get();
        $response = [
            'success' => true,
            'categories' => $category,
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
                'name' => 'required|string|unique:categories',
                'description' => 'required|string',
        ],
            [  
                'name.required' => 'Please enter a category name',
                'name.unique' => 'Sorry, this name has already been used',
                'description.required' => 'Please enter a description for this category',
            ]
        ); 
        

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $response = [
            'success' => true,
            'message' => 'Category added successfully',
            'category' => $category, 
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
        $category = Category::where(['slug' => $slug])->firstOrFail();
        $response = [
            'success' => true,
            'category' => $category, 
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
        $category = Category::where(['slug' => $slug])->firstOrFail();
        $category->update($request->validate([
                'name' => 'required|string|unique:categories',
            ], 
            [  
                'name.required' => 'Please enter a category name',
                'name.unique' => 'Sorry, this name has already been used',
            ]
        )); 
        $response = [
            'success' => true,
            'message' => 'Category updated successfully',
            'category' => $category, 
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
        // return Product::destroy($id);

        $category = Category::where(['slug' => $slug])->firstOrFail();
        $category->delete();
        $response = [
            'success' => true,
            'message' => 'Subscription deleted successfully',
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