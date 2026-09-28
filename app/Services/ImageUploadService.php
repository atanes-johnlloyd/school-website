<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;   // optional, see note below

class ImageUploadService
{
    /**
     * Store an uploaded image on the public disk.
     *
     * @param  UploadedFile  $file
     * @param  string        $folder        e.g. 'avatars', 'subjects', 'announcements'
     * @param  int           $maxWidth      Max width in pixels (resizes if bigger)
     * @param  string        $disk          Defaults to 'public'
     * @return string                       The relative path (to store in DB)
     */
    public function store(
        UploadedFile $file,
        string $folder,
        int $maxWidth = 1200,
        string $disk = 'public'
    ): string {
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::uuid() . '.' . strtolower($extension);
        $path = "{$folder}/{$filename}";

        // Store as-is (without resizing — no Intervention needed)
        Storage::disk($disk)->putFileAs($folder, $file, $filename);

        return $path;
    }

    /**
     * Delete an image if it exists.
     */
    public function delete(?string $path, string $disk = 'public'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}