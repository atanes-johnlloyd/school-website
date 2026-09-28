<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Models\Strand;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class StrandImageController extends Controller
{
    public function __construct(protected ImageUploadService $uploader) {}

    public function update(UploadImageRequest $request, Strand $strand)
    {
        // Delete old
        $this->uploader->delete($strand->image_path);

        // Store new
        $path = $this->uploader->store(
            $request->file('image'),
            'strands',
            800
        );

        // Direct assignment — bypasses mass-assignment entirely
        $strand->image_path = $path;
        $strand->save();

        // Refresh and read from DB
        $strand->refresh();

        return response()->json([
            'message'   => 'Strand image updated.',
            'image_url' => $strand->image_url,
        ]);
    }

    public function destroy(Strand $subject)
    {
        $this->uploader->delete($subject->image_path);
        $subject->update(['image_path' => null]);

        return response()->json(['message' => 'Strand image removed.']);
    }

    /**
     * Bulk-set icon and color (no image file — just metadata).
     */
    public function setMeta(Request $request, Strand $subject)
    {
        $validated = $request->validate([
            'icon'  => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $subject->update($validated);

        return response()->json([
            'message' => 'Strand meta updated.',
            'subject' => $subject->fresh(),
        ]);
    }
}