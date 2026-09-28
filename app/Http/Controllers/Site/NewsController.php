<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search'   => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:30'],
        ]);

        $query = Announcement::query()
            ->schoolWide()
            ->published()
            ->active()
            ->with('author:id,name')
            ->ordered();

        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('body', 'like', "%{$s}%");
            });
        }

        $news = $query->paginate($validated['per_page'] ?? 10);

        $news->getCollection()->transform(fn (Announcement $a) => [
            'id'              => $a->id,
            'title'           => $a->title,
            'body_preview'    => \Str::limit(strip_tags($a->body), 200),
            'image_url'       => $a->image_url,
            'is_pinned'       => $a->is_pinned,
            'author'          => $a->author?->name,
            'published_at'    => $a->published_at?->toIso8601String(),
            'published_human' => $a->published_at?->diffForHumans(),
        ]);

        $payload = [
            'news'    => $news,
            'filters' => [
                'search' => $validated['search'] ?? null,
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Site/News', $payload);
    }

    public function show(Request $request, Announcement $announcement)
    {
        abort_unless(
            $announcement->is_school_wide
            && $announcement->published_at !== null,
            404
        );

        // Expired check
        if ($announcement->expires_at && $announcement->expires_at->isPast()) {
            abort(404);
        }

        $announcement->load('author:id,name');

        $payload = [
            'announcement' => [
                'id'              => $announcement->id,
                'title'           => $announcement->title,
                'body'            => $announcement->body,
                'image_url'       => $announcement->image_url,
                'author'          => $announcement->author?->name,
                'published_at'    => $announcement->published_at?->toIso8601String(),
                'published_human' => $announcement->published_at?->diffForHumans(),
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Site/NewsShow', $payload);
    }
}