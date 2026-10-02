<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'avatar' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'avatar.required' => 'Please choose an image.',
            'avatar.image'    => 'The file must be an image.',
            'avatar.mimes'    => 'Only JPG, PNG, or WebP allowed.',
            'avatar.max'      => 'Image must be 2 MB or smaller.',
        ]);

        $user = $request->user();

        // Delete old avatar if any
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $validated['avatar']->store('avatars', 'public');

        $user->avatar_path = $path;
        $user->save();

        return back()->with('success', 'Profile picture updated.');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->avatar_path = null;
        $user->save();

        return back()->with('success', 'Profile picture removed.');
    }
}