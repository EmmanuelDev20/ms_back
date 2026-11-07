<?php

namespace Database\Seeders;

use App\Models\Images;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Imports the projects exported from the old site (database/seeders/data/proyectos.json).
 *
 * Images were already uploaded to R2 keeping the local folder structure, e.g.
 *   imagenes/7-casa-las-nubes/principal/x.jpg  ->  {R2_IMAGES_BASE}7-casa-las-nubes/principal/x.jpg
 *
 * Safe to run more than once: projects are matched by slug and their gallery is rebuilt.
 *
 *   php artisan db:seed --class=ProjectsSeeder --force
 */
class ProjectsSeeder extends Seeder
{
    private const R2_IMAGES_BASE = 'https://pub-e1e8ae28c57d4a089b60b011b362e52d.r2.dev/images/';

    public function run(): void
    {
        $projects = json_decode(
            file_get_contents(database_path('seeders/data/proyectos.json')),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        DB::transaction(function () use ($projects) {
            foreach ($projects as $data) {
                $project = Project::firstOrNew(['slug' => $data['slug']]);
                $project->url_main_image = $this->r2Url($data['main_image_local']);

                $project->translateOrNew('es')->fill([
                    'name' => $data['name'],
                    'subtitle' => $data['subtitle'],
                    'description' => $data['description'],
                    'work_made' => $data['work_made_html'],
                ]);

                $project->translateOrNew('en')->fill([
                    'name' => $data['name_english'],
                    'subtitle' => $data['subtitle_english'],
                    'description' => $data['description_english'],
                    'work_made' => $data['work_made_english_html'],
                ]);

                $project->save();

                Images::where('project_id', $project->id)->delete();

                foreach ($data['images_local'] as $position => $path) {
                    Images::create([
                        'project_id' => $project->id,
                        'url' => $this->r2Url($path),
                        'position' => $position,
                    ]);
                }

                $this->command?->info("{$data['slug']}: " . count($data['images_local']) . ' imágenes');
            }
        });
    }

    /** "imagenes/<carpeta>/<sub>/<archivo>" -> public R2 URL (same format ImageService stores). */
    private function r2Url(string $localPath): string
    {
        return self::R2_IMAGES_BASE . preg_replace('#^imagenes/#', '', $localPath);
    }
}
