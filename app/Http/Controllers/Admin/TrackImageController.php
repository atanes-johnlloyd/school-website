<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Models\Track;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class TrackImageController extends Controller
{
    public function __construct(protected ImageUploadService $uploader) {}

    public function update(UploadImageRequest $request, Track $track)
    {
        // Delete old
        $this->uploader->delete($track->image_path);

        // Store new
        $path = $this->uploader->store(
            $request->file('image'),
            'tracks',
            800
        );

        // Direct assignment — bypasses mass-assignment entirely
        $track->image_path = $path;
        $track->save();

        // Refresh and read from DB
        $track->refresh();

        return response()->json([
            'message'   => 'Track image updated.',
            'image_url' => $track->image_url,
        ]);
    }

    public function destroy(Track $subject)
    {
        $this->uploader->delete($subject->image_path);
        $subject->update(['image_path' => null]);

        return response()->json(['message' => 'Track image removed.']);
    }

    /**
     * Bulk-set icon and color (no image file — just metadata).
     */
    public function setMeta(Request $request, Track $subject)
    {
        $validated = $request->validate([
            'icon'  => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $subject->update($validated);

        return response()->json([
            'message' => 'Track meta updated.',
            'subject' => $subject->fresh(),
        ]);
    }
}