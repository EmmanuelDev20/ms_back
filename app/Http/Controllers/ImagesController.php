<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ImagesController extends Controller
{
    public function index(Project $project)
    {
        // $images = $project->images;
        $images = $project->images;
        return view('admin.images.index', compact('project', 'images'));
    }

    public function sortImages(Request $request) {
        $position = 1;
        $sorts = $request->sorts;

        foreach($sorts as $sort) {
            $image = Images::find($sort);
            $image->position = $position;
            $image->save();
            $position++;
        }
    }

    public function create()
    {
        //
    }

    // public function store(Request $request, Project $project)
    // {
    //     $request->validate([
    //         'file' => 'required|image|mimes:jpg,jpeg,png',
    //     ]);

    //     $file = $request->file('file');
    //     $path = $file->store('images', 'public');

    //     Images::create([
    //         'project_id' => $project->id,
    //         'url' => '/storage/' . $path
    //     ]);

    //     return response()->json(['message' => 'File uploaded successfully']);
    // }

    public function store(Request $request, Project $project)
    {
        $name = Str::random(10) . $request->file('file')->getClientOriginalName();
        // $ruta = storage_path() . '\app\public\images/' . $name;
        $ruta = public_path('storage/images/'.$name);
        Image::make($request->file('file'))->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($ruta);

        $new_file = '/storage/images/' . $name;

        Images::create([
            'project_id' => $project->id,
            'url' => $new_file
        ]);

        // WORK GREAT
        // $request->validate([
        //     'file' => 'required|image|mimes:jpg,jpeg,png',
        // ]);

        // $file = $request->file('file');

        // // Generate a unique filename for the uploaded image
        // $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        // // Store the original image
        // // $path = $file->storeAs('images', $filename, 'public');
        // $path = $file->store('images', 'public');

        // // Create an instance of the uploaded image using Intervention Image
        // $image = Image::make(storage_path('app/public/' . $path))->resize(800, 600)->save(storage_path('app/public/images/' . $filename));

        // Images::create([
        //     'project_id' => $project->id,
        //     'url' => '/storage/' . $path
        // ]);

        // WORKING PERFECT

        // $file = $request->file('file');
        // $path = $file->store('images', 'public');

        // Images::create([
        //     'project_id' => $project->id,
        //     'url' => '/storage/' . $path
        // ]);

        // return response()->json(['message' => 'File uploaded successfully']);
    }

    public function show(Images $images)
    {
        //
    }

    public function edit(Images $images)
    {
        //
    }

    public function update(Request $request, Images $images)
    {
        //
    }

    public function destroy(Images $image, Project $project)
    {
        $url = str_replace('storage', 'public', $image->url);
        Storage::delete($url);
        $image->delete();
        return redirect()->route('images.index', $project);
    }
}
