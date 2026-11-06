<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BuildNuxt extends Command
{
    protected $signature   = 'nuxt:build';
    protected $description = 'Run nuxt generate to rebuild the frontend';

    public function handle(): int
    {
        $path = env('NUXT_PATH');

        if (!$path || !is_dir($path)) {
            $this->error("NUXT_PATH not set or directory not found: {$path}");
            return 1;
        }

        $log = storage_path('logs/nuxt-build.log');
        $this->info("Starting nuxt generate in {$path} ...");

        $cmd = "cd " . escapeshellarg($path) . " && npm run generate >> " . escapeshellarg($log) . " 2>&1";
        exec($cmd, $output, $code);

        if ($code !== 0) {
            $this->error("Build failed. Check {$log}");
            return 1;
        }

        $this->info("Build completed successfully.");
        return 0;
    }
}
