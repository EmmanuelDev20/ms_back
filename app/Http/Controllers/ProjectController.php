<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Project;
use App\Models\ProjectTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index_api()
    {
        $projects = Project::all();
        return $projects;
    }
    public function show_api($id)
    {
        $projects = Project::with('images')->findOrFail($id);

        return $projects;
    }
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
            'url_main_image' => 'image',
            'name' => 'required',
            'name_english' => 'required'
        ]);
        // $probando_ruta
        // C:\xampp\htdocs\js_projects\ms_ingenieria\ms_scratch_jet_back\storage\app\public\images/cGDBnGDTXIExterior A full.jpg
        // $ruta
        // C:\xampp\htdocs\js_projects\ms_ingenieria\ms_scratch_jet_back\public\images/ng0g883BD1Exterior A full.jpg
        // $probandoruta = storage_path() . '\app\public\images/' . $name;
        // $ruta = public_path('images/'.$name);
        if ($request->file('url_main_image')) {
            $name = Str::random(10) . $request->file('url_main_image')->getClientOriginalName();
            // $ruta = storage_path() . '\app\public\images/' . $name;
            $ruta = storage_path("app\public\images/".$name);
            $probandoruta = public_path('storage/images/'.$name);
            // dd($ruta, $probandoruta);

            Image::make($request->file('url_main_image'))->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($probandoruta);
        }

        $project = Project::create([
            'url_main_image' => $request->file('url_main_image') ? '/storage/images/' . $name : null
        ]);

        ProjectTranslation::create([
            'project_id' => $project->id,
            'locale' => 'es',
            'name' => $request->name,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'work_made' => $request->work_made
        ]);

        ProjectTranslation::create([
            'project_id' => $project->id,
            'locale' => 'en',
            'name' => $request->name_english,
            'subtitle' => $request->subtitle_english,
            'description' => $request->description_english,
            'work_made' => $request->work_made_english
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
        $imageName = time() . rand(1, 100) . '.' . $image->extension();
        $image->move(public_path('images'), $imageName);
        // return response()->json(['success' => $imageName]);
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
        $spanish_data = [];
        $english_data = [];

        foreach ($project->translations as $project_lang) :
            if ($project_lang->locale === 'es') :
                $spanish_data = $project_lang;
            else :
                $english_data = $project_lang;
            endif;
        endforeach;

        return view('admin.projects.edit', compact('project', 'spanish_data', 'english_data'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'url_main_image' => 'image'
        ]);

        if ($request->file('url_main_image')) {
            $name = Str::random(10) . $request->file('url_main_image')->getClientOriginalName();
            // $ruta = storage_path() . '\app\public\images/' . $name;
            $probandoruta = public_path('storage/images/'.$name);
            Image::make($request->file('url_main_image'))->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($probandoruta);

            $new_url_main_image = '/storage/images/' . $name;
            $project->url_main_image = $new_url_main_image;
        }

        foreach ($project->translations as $project_lang) :
            if ($project_lang->locale === 'es') :
            $project_lang->update([
                'name' => $request->name,
                'subtitle' => $request->subtitle,
                'description' => $request->description,
                'work_made' => $request->work_made
            ]);
            else :
            $project_lang->update([
                'name' => $request->name_english,
                'subtitle' => $request->subtitle_english,
                'description' => $request->description_english,
                'work_made' => $request->work_made_english
            ]);
            endif;
        endforeach;

        // foreach ($project->translations as $project_lang) :
        //     if ($project_lang->locale === 'es') :
        //         $project_lang->name = $request->name;
        //         $project_lang->subtitle = $request->subtitle;
        //         $project_lang->description = $request->description;
        //         $project_lang->work_made = $request->work_made;
        //     else :
        //         $project_lang->name = $request->name_english;
        //         $project_lang->subtitle = $request->subtitle_english;
        //         $project_lang->description = $request->description_english;
        //         $project_lang->work_made = $request->work_made_english;
        //     endif;
        // endforeach;

        // $project->save();

        return redirect()->route('project.index');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('project.index');
    }
}
