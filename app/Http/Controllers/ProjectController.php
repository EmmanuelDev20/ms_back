<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'url_main_image' => 'image'
        ]);

        if ($request->file('url_main_image')) {
            $name = Str::random(10) . $request->file('url_main_image')->getClientOriginalName();
            $ruta = storage_path() . '\app\public\images/' . $name;
            Image::make($request->file('url_main_image'))->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($ruta);
        }

        Project::create([
            'url_main_image' => $request->file('url_main_image') ? '/storage/images/' . $name : null,
            'name' => $request->name,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'work_made' => $request->work_made,
        ]);

        return redirect()->route('project.index');
    }

    public function images(Project $project)
    {
        return view('admin.projects.images', compact('project'));
    }

    public function storeimages(Request $request)
    {
        $image = $request->file('file');
        $imageName = time().rand(1,100) . '.' . $image->extension();
        $image->move(public_path('images'), $imageName);
        return response()->json(['success' => $imageName]);
        //first try
        // $images = $request->file('file')->store('public/images');
        // $url = Storage::url($images);

        // Images::create([
        //     'url' => $url,
        //     'project_id' => $project->id
        // ]);
        // second try
        // if ($request->file('file')) {
        //     $name = Str::random(10) . $request->file('file')->getClientOriginalName();
        //     $ruta = storage_path() . '\app\public\images/' . $name;
        //     Image::make($request->file('file'))->resize(1200, null, function ($constraint) {
        //         $constraint->aspectRatio();
        //     })->save($ruta);
        // }

        // Images::create([
        //     'url' => '/storage/images/' . $name,
        //     'project_id' => $project->id
        // ]);
        // return view('admin.projects.images', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'url_main_image' => 'image'
        ]);

        if ($request->file('url_main_image')) {
            $name = Str::random(10) . $request->file('url_main_image')->getClientOriginalName();
            $ruta = storage_path() . '\app\public\images/' . $name;
            Image::make($request->file('url_main_image'))->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($ruta);

            $new_url_main_image = '/storage/images/' . $name;
            $project->url_main_image = $new_url_main_image;
        }

        $project->name = $request->name;
        $project->subtitle = $request->subtitle;
        $project->description = $request->description;
        $project->work_made = $request->work_made;

        $project->save();

        return redirect()->route('project.index');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('project.index');
    }
}
