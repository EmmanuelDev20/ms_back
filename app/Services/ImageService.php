<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ImageService
{
    protected string $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.default', 'local');
    }

    public function upload(UploadedFile $file, string $folder = 'images'): string
    {
        $name = Str::random(10) . $file->getClientOriginalName();

        $resized = Image::make($file)->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $contents = (string) $resized->encode();
        $path = $folder . '/' . $name;

        Storage::disk($this->disk)->put($path, $contents);

        return Storage::disk($this->disk)->url($path);
    }

    public function delete(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH);

        if ($path) {
            $path = ltrim($path, '/');
            return Storage::disk($this->disk)->delete($path);
        }

        return false;
    }

    public function getDisk(): string
    {
        return $this->disk;
    }
}
