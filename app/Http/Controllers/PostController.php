<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $post = Post::orderBy('created_at', 'desc')->with('tags', 'categories', 'users')->get();
        $response = [
            'success' => true,
            'posts' => $post,
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
                'title' => 'required|string|unique:posts',
                'post' => 'required',
                'tag_id' => 'required',
                'cat_id' => 'required',
                'user_id' => 'required',
                'image' => 'required',
            ], 
            [  
                'title.required' => 'Please enter blogpost title',
                'title.unique' => 'Sorry, this title has already been used',
                'post.required' => 'Please add a blogpost',
                'tag_id.required' => 'Please select blogpost tags',
                'cat_id.required' => 'Please select blogpost category',
                'user_id.required' => 'Please select blogpost author',
                'image.required' => 'Please upload blogpost image',
            ]
        ); 
        
        $filename = "";
        if ($request->file('image')) {
            $filename = $request->file('image')->store('images/thumbnail', 'public');
        } else {
            $filename = "null";
        }
        
        $post = Post::create([
            'title' => $request->title,
            'post' => $request->post,
            'image' => $filename,
            'tag_id' => $request->tag_id,
            'cat_id' => $request->cat_id,
            'user_id' => $request->user_id,
            'views' => 0

        ]);

        $response = [
            'success' => true,
            'message' => 'Post added successfully',
            'post' => $post, 
            
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
        $post = Post::where(['slug' => $slug])->firstOrFail();
        $response = [
            'success' => true,
            'post' => $post, 
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
        $request->validate([
                'title' => 'required|unique:posts',
                'post' => 'required',
                'tag_id' => 'required',
                'cat_id' => 'required',
            ], 
            [  
                'title.required' => 'Please enter blogpost title',
                'title.unique' => 'Sorry, this title has already been used',
                'post.required' => 'Please add a blogpost',
                'tag_id.required' => 'Please select blogpost tags',
                'cat_id.required' => 'Please select blogpost category',
            ]
        );

        $post = Post::where(['slug' => $slug])->firstOrFail();
        $edit = $request->all();

        $filename = "";
        if ($request->file('new_image')) {
            if (Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $filename = $request->file('new_image')->store('images/thumbnail', 'public');
            $edit['image'] = $filename;
        } else {
            $filename = $post->image;
            $edit['image'] = $filename;
        };
        

        $post->update($edit);

        $response = [
            'success' => true,
            'message' => 'Post updated successfully',
            'post' => $post, 
            
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
        $post = Post::where(['slug' => $slug])->firstOrFail()->delete();
        $response = [
            'success' => true,
            'message' => 'Post deleted successfully',
        ];

        return response($response, 200);
    }

    public function search($search) { 
        $result = Post::where('title', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->with('categories')->get();

        $response = [
            'success' => true,
            'result' => $result 
        ];

        return response($response, 200);
    }
}