<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Models\Subject;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class SubjectImageController extends Controller
{
    public function __construct(protected ImageUploadService $uploader) {}

    public function update(UploadImageRequest $request, Subject $subject)
    {
        // Delete old
        $this->uploader->delete($subject->image_path);

        // Store new — folder MUST be 'subjects', not 'tracks'
        $path = $this->uploader->store(
            $request->file('image'),
            'subjects',
            800
        );

        // Direct assignment (avoids any mass-assignment quirks)
        $subject->image_path = $path;
        $subject->save();

        $subject->refresh();

        return response()->json([
            'message'   => 'Subject image updated.',
            'image_url' => $subject->image_url,
        ]);
    }

    public function destroy(Subject $subject)
    {
        $this->uploader->delete($subject->image_path);
        $subject->image_path = null;
        $subject->save();

        return response()->json(['message' => 'Subject image removed.']);
    }

    public function setMeta(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'icon'  => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        if (isset($validated['icon']))  $subject->icon  = $validated['icon'];
        if (isset($validated['color'])) $subject->color = $validated['color'];
        $subject->save();

        return response()->json([
            'message' => 'Subject meta updated.',
            'subject' => $subject->fresh(),
        ]);
    }
}