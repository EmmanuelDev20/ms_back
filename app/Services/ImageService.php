<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

class ImageService
{
    /** Maximum width (px) for stored images. Smaller images are never upscaled. */
    public const MAX_WIDTH = 1200;

    protected string $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.images', 'r2');
    }

    public function upload(UploadedFile $file, string $folder = 'images'): string
    {
        $name = Str::random(10) . $file->getClientOriginalName();

        $image = (new ImageManager(new Driver()))
            ->read($file->getRealPath())
            ->scaleDown(width: self::MAX_WIDTH);

        $contents = (string) $image->encode();
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
