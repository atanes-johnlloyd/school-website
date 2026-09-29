<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageService
{
    public function store(
        UploadedFile $file,
        string $folder,
        string $disk = 'local'
    ): string {
        $filename = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension() ?: 'bin');
        return Storage::disk($disk)->putFileAs($folder, $file, $filename);
    }

    public function delete(?string $path, string $disk = 'local'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}