<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvatarController extends Controller
{
    public function __construct(protected ImageUploadService $uploader) {}

    /**
     * Upload / replace the current user's avatar.
     */
    public function update(UploadImageRequest $request)
    {
        $user = $request->user();

        DB::transaction(function () use ($user, $request) {
            // Delete old avatar
            $this->uploader->delete($user->avatar_path);

            // Store new
            $path = $this->uploader->store(
                $request->file('image'),
                'avatars',
                512
            );

            $user->update(['avatar_path' => $path]);
        });

        return response()->json([
            'message'    => 'Avatar updated.',
            'avatar_url' => $user->fresh()->avatar_url,
        ]);
    }

    /**
     * Remove the current user's avatar.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        $this->uploader->delete($user->avatar_path);
        $user->update(['avatar_path' => null]);

        return response()->json(['message' => 'Avatar removed.']);
    }
}