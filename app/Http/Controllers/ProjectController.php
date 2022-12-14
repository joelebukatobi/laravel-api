<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
         /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $projects = Project::orderBy('created_at', 'asc')->get();
        $response = [
            'success' => true,
            'projects' => $projects,
        ];
        
        return response ($response, 200);
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
        $project = Project::where(['slug' => $slug])->orderBy('created_at', 'asc')->firstOrFail();
        $response = [
            'success' => true,
            'project' => $project, 
        ];

        return response($response, 200);
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
            'name' => 'required|string',
            'description' => 'required',
            'image' => 'required',
            'technologies' => 'required',
            'website' => 'required',
        ],
        [  
            'name.required' => 'Please enter a name for this project',
            'description.required' => 'Please enter a description for this project',
            'image.required' => 'Please input an image for this project',
            'technologies.required' => 'Please enter an technologies used in this project',
            'website.required' => 'Please enter the project website link',
        ]
        ); 
        
        $filename = "";
        if ($request->file('image')) {
            $filename = $request->file('image')->store('images/projects', 'public');
        } else {
            $filename = "null";
        }
        
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $filename,
            'technologies' => $request->technologies,
            'website' => $request->website,
            'design' => $request->design,
        ]);

        $response = [
            'success' => true,
            'message' => 'Project added successfully',
            'project' => $project, 
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
            'name' => 'required|string',
            'description' => 'required',
            'image' => 'required',
            'technologies' => 'required',
            'website' => 'required',
        ],
        [  
            'name.required' => 'Please enter a name for this project',
            'description.required' => 'Please enter a description for this project',
            'image.required' => 'Please input an image for this project',
            'technologies.required' => 'Please enter an technologies used in this project',
            'website.required' => 'Please enter the project website link',
        ]
        ); 

        $project = Project::where(['slug' => $slug])->firstOrFail();
        $edit = $request->all();

        $filename = "";
        if ($request->file('new_image')) {
            if (Storage::disk('public')->exists($project->image)) {
                Storage::disk('public')->delete($project->image);
            }
            $filename = $request->file('new_image')->store('images/projects', 'public');
            $edit['image'] = $filename;
        } else {
            $filename = $project->image;
            $edit['image'] = $filename;
        };

        $project->update($edit);

        $response = [
            'success' => true,
            'message' => 'Project added successfully',
            'project' => $project, 
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
        $project = Project::where(['slug' => $slug])->firstOrFail()->delete();
        $response = [
            'success' => true,
            'message' => 'Project deleted successfully',
        ];

        return response($response, 200);
    }
}