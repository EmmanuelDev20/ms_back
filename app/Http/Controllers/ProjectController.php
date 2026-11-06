<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Project;
use App\Models\ProjectTranslation;
use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index_api()
    {
        $projects = Project::all();
        return $projects;
    }

    public function show_api($slug)
    {
        $project = Project::with('images')->where('slug', $slug)->firstOrFail();
        return $project;
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
            'name_english' => 'required',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $mainImageUrl = null;
        if ($request->file('url_main_image')) {
            $mainImageUrl = $this->imageService->upload($request->file('url_main_image'));
        }

        $slug = $this->uniqueSlug(Str::slug($request->name));

        $project = Project::create([
            'url_main_image' => $mainImageUrl,
            'slug'           => $slug,
        ]);

        ProjectTranslation::create([
            'project_id' => $project->id,
            'locale' => 'es',
            'name' => $request->name,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'work_made' => $request->work_made,
        ]);

        ProjectTranslation::create([
            'project_id' => $project->id,
            'locale' => 'en',
            'name' => $request->name_english,
            'subtitle' => $request->subtitle_english,
            'description' => $request->description_english,
            'work_made' => $request->work_made_english,
        ]);

        if ($request->hasFile('gallery_images')) {
            $layouts  = $request->input('gallery_layouts', []);
            $position = 1;
            foreach ($request->file('gallery_images') as $index => $file) {
                $url = $this->imageService->upload($file);
                Images::create([
                    'project_id' => $project->id,
                    'url'        => $url,
                    'position'   => $position,
                    'layout'     => $layouts[$index] ?? null,
                ]);
                $position++;
            }
        }

        $this->triggerNuxtBuild();
        return redirect()->route('project.index');
    }

    public function images(Project $project)
    {
        $images = $project->images;
        return view('admin.images.index', compact('project', 'images'));
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
            'url_main_image' => 'image',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png|max:10240',
        ]);

        if ($request->file('url_main_image')) {
            if ($project->url_main_image) {
                $this->imageService->delete($project->url_main_image);
            }
            $project->url_main_image = $this->imageService->upload($request->file('url_main_image'));
        }

        foreach ($project->translations as $project_lang) :
            if ($project_lang->locale === 'es') :
                if ($request->name && Str::slug($request->name) !== Str::slug($project_lang->name)) {
                    $project->slug = $this->uniqueSlug(Str::slug($request->name), $project->id);
                }
                $project_lang->update([
                    'name' => $request->name,
                    'subtitle' => $request->subtitle,
                    'description' => $request->description,
                    'work_made' => $request->work_made,
                ]);
            else :
                $project_lang->update([
                    'name' => $request->name_english,
                    'subtitle' => $request->subtitle_english,
                    'description' => $request->description_english,
                    'work_made' => $request->work_made_english,
                ]);
            endif;
        endforeach;

        $project->save();

        if ($request->hasFile('gallery_images')) {
            $layouts     = $request->input('gallery_layouts', []);
            $maxPosition = $project->images->max('position') ?? 0;
            $position    = $maxPosition + 1;
            foreach ($request->file('gallery_images') as $index => $file) {
                $url = $this->imageService->upload($file);
                Images::create([
                    'project_id' => $project->id,
                    'url'        => $url,
                    'position'   => $position,
                    'layout'     => $layouts[$index] ?? null,
                ]);
                $position++;
            }
        }

        $this->triggerNuxtBuild();
        return redirect()->route('project.index');
    }

    public function destroy(Project $project)
    {
        foreach ($project->images as $image) {
            $this->imageService->delete($image->url);
        }

        if ($project->url_main_image) {
            $this->imageService->delete($project->url_main_image);
        }

        $project->delete();
        return redirect()->route('project.index');
    }

    public function setLayout(Request $request, Project $project)
    {
        $project->layout = $request->layout;
        $project->save();
        return redirect()->route('images.index', $project)->with('success', 'Layout guardado.');
    }

    private function triggerNuxtBuild(): void
    {
        $hook = env('NETLIFY_BUILD_HOOK');
        if (!$hook) return;

        $ch = curl_init($hook);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_exec($ch);
        curl_close($ch);
    }

    private function uniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = $base;
        $i    = 2;
        while (
            Project::where('slug', $slug)
                ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}