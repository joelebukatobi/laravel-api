<?php

namespace App\Http\Controllers;


use Storage;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

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
        $post = Post::with(['tags', 'category', 'user'])->orderBy('created_at', 'asc')->get();
        $response = [
            'success' => true,
            'posts' => $post,
        ];
        
        return response ($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
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
                'post' => 'required|string',
                'cat_id' => 'required|string',
                'user_id' => 'required|string',
                'image' => 'required',
            ], 
            [  
                'title.required' => 'Please enter blogpost title',
                'title.unique' => 'Sorry, this title has already been used',
                'post.required' => 'Please add a blogpost',
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
            'cat_id' => $request->cat_id,
            'user_id' => $request->user_id,
            'views' => 0
        ]);
        
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

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
        $post = Post::where(['slug' => $slug])->with( 'category','tags', 'user')->orderBy('created_at', 'asc')->firstOrFail();
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
                'cat_id' => 'required|integer',
                'user_id' => 'required|integer'
            ], 
            [  
                'title.required' => 'Please enter post title',
                'title.unique' => 'Sorry, this post title has already been used',
                'post.required' => 'Please add a post',
                'cat_id.required' => 'Please select post category',
                'user_id.required' => 'Please add post author',
                'cat_id.integer' => 'Please select post category',
                'user_id.integer' => 'Please select post author',
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

        if ($request->tags) {
            $post->tags()->sync($request->tags);
        }

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