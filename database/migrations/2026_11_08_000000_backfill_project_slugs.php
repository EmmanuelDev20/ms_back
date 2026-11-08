<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Nuxt builds project pages by slug (/casa-las-nubes). Give every project that
 * still has no slug one generated from its Spanish name (unique, like the admin does).
 */
return new class extends Migration
{
    public function up(): void
    {
        $projects = DB::table('projects')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->pluck('id');

        foreach ($projects as $id) {
            $name = DB::table('project_translations')
                ->where('project_id', $id)
                ->orderByRaw("locale = 'es' desc")
                ->value('name');

            $base = Str::slug($name ?: 'proyecto-' . $id);
            $slug = $base;
            $i = 2;
            while (DB::table('projects')->where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $base . '-' . $i++;
            }

            DB::table('projects')->where('id', $id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // Slugs are kept: removing them would break the published URLs.
    }
};
