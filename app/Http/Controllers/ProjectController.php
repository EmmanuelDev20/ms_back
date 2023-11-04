<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
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

    public function show(Project $project)
    {
        //
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
