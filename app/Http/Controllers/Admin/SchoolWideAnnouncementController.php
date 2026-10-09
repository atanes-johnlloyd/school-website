<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use App\Services\ImageUploadService;
use App\Services\Notification\NotificationService;
use App\Support\AuditContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SchoolWideAnnouncementController extends Controller
{
    public function __construct(
        protected ImageUploadService $uploader,
        protected NotificationService $notifications,
    ) {}

    public function index(): Response
    {
        return Inertia::render('Admin/SchoolNews/Index');
    }

    public function list(Request $request)
    {
        $validated = $request->validate([
            'status'   => ['nullable', 'in:published,draft,expired'],
            'priority' => ['nullable', 'in:normal,important,urgent'],
            'pinned'   => ['nullable', 'boolean'],
            'search'   => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:10', 'max:100'],
        ]);

        $status   = $validated['status']   ?? null;
        $priority = $validated['priority'] ?? null;
        $pinned   = $validated['pinned']   ?? null;
        $search   = $validated['search']   ?? null;
        $perPage  = $validated['per_page'] ?? 12;

        $query = Announcement::query()->schoolWide()->with('author:id,name');

        if ($status === 'draft') {
            $query->whereNull('published_at');
        } elseif ($status === 'published') {
            $query->whereNotNull('published_at')
                ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
        } elseif ($status === 'expired') {
            $query->whereNotNull('published_at')
                ->whereNotNull('expires_at')
                ->where('expires_at', '<=', now());
        }

        if ($priority) $query->where('priority', $priority);
        if ($pinned === true || $pinned === 'true' || $pinned === '1' || $pinned === 1) {
            $query->where('is_pinned', true);
        }
        if ($search) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")
                                        ->orWhere('body', 'like', "%{$search}%"));
        }

        $query->orderedByImportance();

        $announcements = $query->paginate($perPage);

        $announcements->getCollection()->transform(fn (Announcement $a) => [
            'id'           => $a->id,
            'title'        => $a->title,
            'body'         => $a->body,
            'excerpt'      => mb_strimwidth(strip_tags($a->body), 0, 160, '…'),
            'image_url'    => $a->image_url,
            'priority'     => $a->priority ?? 'normal',
            'is_pinned'    => (bool) $a->is_pinned,
            'is_draft'     => $a->is_draft,
            'is_expired'   => $a->is_expired,
            'author'       => $a->author?->name,
            'published_at' => $a->published_at?->toIso8601String(),
            'expires_at'   => $a->expires_at?->toIso8601String(),
            'created_at'   => $a->created_at?->toIso8601String(),
        ]);

        $base = Announcement::query()->schoolWide();
        $counts = [
            'total'     => (clone $base)->count(),
            'published' => (clone $base)
                ->whereNotNull('published_at')
                ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
                ->count(),
            'drafts'    => (clone $base)->whereNull('published_at')->count(),
            'pinned'    => (clone $base)->where('is_pinned', true)->count(),
            'urgent'    => (clone $base)->where('priority', 'urgent')
                ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
                ->count(),
        ];

        return response()->json([
            'announcements' => $announcements,
            'filters'       => [
                'status'   => $status,
                'priority' => $priority,
                'pinned'   => $pinned,
                'search'   => $search,
            ],
            'counts' => $counts,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/SchoolNews/Form', ['announcement' => null]);
    }

    public function edit(Announcement $announcement): Response
    {
        abort_unless($announcement->is_school_wide, 404);

        return Inertia::render('Admin/SchoolNews/Form', [
            'announcement' => [
                'id'           => $announcement->id,
                'title'        => $announcement->title,
                'body'         => $announcement->body,
                'image_url'    => $announcement->image_url,
                'priority'     => $announcement->priority ?? 'normal',
                'is_pinned'    => (bool) $announcement->is_pinned,
                'is_published' => ! is_null($announcement->published_at),
                'expires_at'   => $announcement->expires_at?->format('Y-m-d'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        $announcement = AuditContext::wrap('create_announcement', function () use ($request, $validated) {
            return DB::transaction(function () use ($request, $validated) {
                $imagePath = null;
                if ($request->hasFile('image')) {
                    $imagePath = $this->uploader->store($request->file('image'), 'school-news', 1200);
                }

                $isPublished = $request->boolean('is_published');

                return Announcement::create([
                    'created_by'     => $request->user()->id,
                    'class_id'       => null,
                    'is_school_wide' => true,
                    'title'          => $validated['title'],
                    'body'           => $validated['body'],
                    'priority'       => $validated['priority'],
                    'is_pinned'      => $request->boolean('is_pinned'),
                    'image_path'     => $imagePath,
                    'published_at'   => $isPublished ? now() : null,
                    'expires_at'     => $this->resolveExpiresAt($validated, $isPublished),
                ]);
            });
        });

        if ($announcement->priority === Announcement::PRIORITY_URGENT && $announcement->published_at) {
            $this->emailAllUsers($announcement);
        }

        return response()->json([
            'message'      => $announcement->is_draft ? 'Draft saved.' : 'Announcement published.',
            'announcement' => $announcement,
        ], 201);
    }

    public function update(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->is_school_wide, 404);

        $validated = $this->validatePayload($request);

        $wasPublished = ! is_null($announcement->published_at);
        $wasUrgent    = $announcement->priority === Announcement::PRIORITY_URGENT;

        AuditContext::wrap('update_announcement', function () use ($request, $announcement, $validated) {
            DB::transaction(function () use ($request, $announcement, $validated) {
            if ($request->boolean('remove_image') && ! $request->hasFile('image')) {
                $this->uploader->delete($announcement->image_path);
                $announcement->image_path = null;
            }    
            if ($request->hasFile('image')) {
                    $this->uploader->delete($announcement->image_path);
                    $announcement->image_path = $this->uploader->store($request->file('image'), 'school-news', 1200);
                }

                $isPublished = $request->boolean('is_published');

                $announcement->fill([
                    'title'        => $validated['title'],
                    'body'         => $validated['body'],
                    'priority'     => $validated['priority'],
                    'is_pinned'    => $request->boolean('is_pinned'),
                    'published_at' => $isPublished ? ($announcement->published_at ?? now()) : null,
                    'expires_at'   => $this->resolveExpiresAt($validated, $isPublished),
                ]);

                $announcement->save();
            });
        });

        $announcement->refresh();

        $isNowUrgent = $announcement->priority === Announcement::PRIORITY_URGENT && $announcement->published_at;
        if ($isNowUrgent && ! ($wasPublished && $wasUrgent)) {
            $this->emailAllUsers($announcement);
        }

        return response()->json([
            'message'      => 'Announcement updated.',
            'announcement' => $announcement,
        ]);
    }

    public function show(Announcement $announcement)
    {
        abort_unless($announcement->is_school_wide, 404);

        return response()->json([
            'announcement' => $announcement->load('author:id,name'),
        ]);
    }

    public function togglePublish(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->is_school_wide, 404);

        $willPublish = is_null($announcement->published_at);

        AuditContext::wrap($willPublish ? 'publish_announcement' : 'unpublish_announcement', function () use ($announcement, $willPublish) {
            $announcement->update([
                'published_at' => $willPublish ? now() : null,
            ]);
        });

        if ($willPublish && $announcement->priority === Announcement::PRIORITY_URGENT) {
            $this->emailAllUsers($announcement);
        }

        return response()->json([
            'message'      => $willPublish ? 'Announcement published.' : 'Announcement unpublished.',
            'is_published' => $willPublish,
        ]);
    }

    public function togglePin(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->is_school_wide, 404);

        $willPin = ! $announcement->is_pinned;

        AuditContext::wrap($willPin ? 'pin_announcement' : 'unpin_announcement', function () use ($announcement, $willPin) {
            $announcement->update(['is_pinned' => $willPin]);
        });

        return response()->json([
            'message'   => $willPin ? 'Pinned.' : 'Unpinned.',
            'is_pinned' => $willPin,
        ]);
    }

    public function destroy(Request $request, Announcement $announcement)
    {
        abort_unless($announcement->is_school_wide, 404);

        AuditContext::wrap('delete_announcement', function () use ($announcement) {
            $this->uploader->delete($announcement->image_path);
            $announcement->delete();
        });

        return response()->json(['message' => 'Announcement deleted.']);
    }

    protected function validatePayload(Request $request): array
    {
        return $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'body'         => ['required', 'string', 'max:5000'],
            'priority'     => ['required', 'in:normal,important,urgent'],
            'is_pinned'    => ['boolean'],
            'is_published' => ['boolean'],
            'expires_at'   => ['nullable', 'date', 'after:today'],
            'image'        => ['nullable', 'file', 'image', 'max:' . \App\Models\SystemSetting::maxFileUploadKb()],
        ], [
            'title.required'    => 'Announcement title is required.',
            'body.required'     => 'Announcement body is required.',
            'body.max'          => 'Body cannot exceed 5,000 characters.',
            'priority.required' => 'Choose a priority level.',
            'expires_at.after'  => 'Expiry date must be in the future.',
            'image.max'         => 'Cover image must be 4 MB or smaller.',
        ]);
    }

    protected function resolveExpiresAt(array $validated, bool $isPublished): ?\Carbon\Carbon
    {
        if (! $isPublished) return null;

        if (! empty($validated['expires_at'])) {
            return \Carbon\Carbon::parse($validated['expires_at']);
        }

        $defaultDays = \App\Models\SystemSetting::announcementDays();

        return match ($validated['priority']) {
            Announcement::PRIORITY_URGENT    => now()->addHours(72),
            Announcement::PRIORITY_IMPORTANT => now()->addDays($defaultDays),
            default                          => now()->addDays($defaultDays),
        };
    }

    protected function emailAllUsers(Announcement $announcement): void
    {
        User::where('status', 'active')
            ->select('id', 'name', 'email')
            ->chunk(200, function ($users) use ($announcement) {
                foreach ($users as $user) {
                    try {
                        $this->notifications->send(
                            $user->email,
                            'Urgent: ' . $announcement->title,
                            'urgent-announcement',
                            [
                                'full_name'          => $user->name,
                                'announcement_title' => $announcement->title,
                                'announcement_body'  => $announcement->body,
                                'announcement_link'  => url('/dashboard'),
                                'posted_at'          => $announcement->published_at->format('F d, Y \a\t g:i A'),
                            ]
                        );
                    } catch (\Throwable $e) {
                        \Log::error('Urgent announcement email failed', [
                            'user_id'         => $user->id,
                            'announcement_id' => $announcement->id,
                            'error'           => $e->getMessage(),
                        ]);
                    }
                }
            });
    }
}