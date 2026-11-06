<?php
namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Project $project)
    {
        $images = $project->images;
        return view('admin.images.index', compact('project', 'images'));
    }

    public function sortImages(Request $request)
    {
        $position = 1;
        foreach ($request->sorts as $sort) {
            $image = Images::find($sort);
            $image->position = $position++;
            $image->save();
        }
    }

    // Upload to the generic dropzone (no block)
    public function store(Request $request, Project $project)
    {
        $url  = $this->imageService->upload($request->file('file'));
        $max  = $project->images->max('position') ?? 0;
        $image = Images::create([
            'project_id' => $project->id,
            'url'        => $url,
            'position'   => $max + 1,
            'block_id'   => $request->block_id ?: null,
            'layout'     => $request->layout ?: null,
        ]);
        return response()->json([
            'id'           => $image->id,
            'url'          => asset($image->url),
            'setLayoutUrl' => route('images.setLayout', ['project' => $project, 'image' => $image]),
            'deleteUrl'    => route('images.destroy',   ['image' => $image, 'project' => $project]),
        ]);
    }

    // Upload directly into a named block
    public function storeInBlock(Request $request, Project $project, $blockId)
    {
        $url  = $this->imageService->upload($request->file('file'));
        $max  = $project->images->max('position') ?? 0;
        $image = Images::create([
            'project_id' => $project->id,
            'url'        => $url,
            'position'   => $max + 1,
            'block_id'   => $blockId,
            'layout'     => $request->layout ?: null,
        ]);
        return response()->json([
            'id'        => $image->id,
            'url'       => asset($image->url),
            'deleteUrl' => route('images.destroy', ['image' => $image, 'project' => $project]),
        ]);
    }

    // Layout per single image (kept for backwards compat)
    public function setImageLayout(Request $request, Project $project, Images $image)
    {
        $image->update(['layout' => $request->layout ?: null]);
        return response()->json(['ok' => true]);
    }

    // Layout for entire block (all images sharing block_id)
    public function setBlockLayout(Request $request, Project $project, $blockId)
    {
        Images::where('project_id', $project->id)
              ->where('block_id', $blockId)
              ->update(['layout' => $request->layout ?: null]);
        return response()->json(['ok' => true]);
    }

    // Delete entire block
    public function destroyBlock(Request $request, Project $project, $blockId)
    {
        $images = Images::where('project_id', $project->id)
                        ->where('block_id', $blockId)
                        ->get();
        foreach ($images as $img) {
            $this->imageService->delete($img->url);
            $img->delete();
        }
        return response()->json(['ok' => true]);
    }

    // Delete single image
    public function destroy(Images $image, Project $project)
    {
        $this->imageService->delete($image->url);
        $image->delete();
        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }
        return back();
    }
}
