<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    /* ═══════════════ PUBLIC — store submission (KEEP YOUR EXISTING) ═══════════════ */
    public function store(StoreContactMessageRequest $request)
    {
        $message = ContactMessage::create([
            'name'    => $request->validated('name'),
            'email'   => $request->validated('email'),
            'subject' => $request->validated('subject'),
            'message' => $request->validated('message'),
            'is_read' => false,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Thank you. We\'ll get back to you soon.',
                'data'    => ['id' => $message->id],
            ], 201);
        }

        return back()->with('success', 'Thank you. We\'ll get back to you soon.');
    }

    /* ═══════════════ ADMIN — page shell ═══════════════ */
    public function index(): Response
    {
        return Inertia::render('Admin/ContactMessages/Index');
    }

    /* ═══════════════ ADMIN — JSON list ═══════════════ */
    public function list(Request $request)
    {
        $validated = $request->validate([
            'status'   => ['nullable', 'in:read,unread'],
            'when'     => ['nullable', 'in:today,week,month'],
            'search'   => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'  => ['nullable', 'in:created_at,name,subject'],
            'sort_dir' => ['nullable', 'in:asc,desc'],
        ]);

        $status  = $validated['status']   ?? null;
        $when    = $validated['when']     ?? null;
        $search  = $validated['search']   ?? null;
        $perPage = $validated['per_page'] ?? 15;
        $sortBy  = $validated['sort_by']  ?? 'created_at';
        $sortDir = $validated['sort_dir'] ?? 'desc';

        $query = ContactMessage::query();

        if ($status === 'unread') {
            $query->where('is_read', false);
        } elseif ($status === 'read') {
            $query->where('is_read', true);
        }

        if ($when === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($when === 'week') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($when === 'month') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sortBy, $sortDir);

        $messages = $query->paginate($perPage);

        $messages->getCollection()->transform(fn (ContactMessage $m) => [
            'id'         => $m->id,
            'name'       => $m->name,
            'email'      => $m->email,
            'subject'    => $m->subject,
            'message'    => $m->message,
            'excerpt'    => mb_strimwidth(strip_tags($m->message), 0, 160, '…'),
            'is_read'    => (bool) $m->is_read,
            'created_at' => $m->created_at?->toIso8601String(),
        ]);

        $counts = [
            'total'      => ContactMessage::count(),
            'unread'     => ContactMessage::where('is_read', false)->count(),
            'read'       => ContactMessage::where('is_read', true)->count(),
            'this_week'  => ContactMessage::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return response()->json([
            'messages' => $messages,
            'filters'  => [
                'status' => $status,
                'when'   => $when,
                'search' => $search,
            ],
            'sort'   => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => $counts,
        ]);
    }

    /* ═══════════════ ADMIN — single message JSON ═══════════════ */
    public function show(Request $request, ContactMessage $contactMessage)
    {
        return response()->json([
            'message' => [
                'id'         => $contactMessage->id,
                'name'       => $contactMessage->name,
                'email'      => $contactMessage->email,
                'subject'    => $contactMessage->subject,
                'message'    => $contactMessage->message,
                'is_read'    => (bool) $contactMessage->is_read,
                'created_at' => $contactMessage->created_at?->toIso8601String(),
            ],
        ]);
    }

    /* ═══════════════ ADMIN — toggle read state ═══════════════ */
    public function toggleRead(Request $request, ContactMessage $contactMessage)
    {
        $contactMessage->update(['is_read' => ! $contactMessage->is_read]);

        return response()->json([
            'message' => $contactMessage->is_read ? 'Marked as read.' : 'Marked as unread.',
            'is_read' => (bool) $contactMessage->is_read,
        ]);
    }

    /* ═══════════════ ADMIN — delete ═══════════════ */
    public function destroy(Request $request, ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return response()->json(['message' => 'Message deleted.']);
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'body'    => ['required', 'string', 'max:5000'],
        ]);

        if (! $contactMessage->email) {
            return response()->json([
                'message' => 'This message has no reply-to email address.',
            ], 422);
        }

        try {
            app(\App\Services\Notification\NotificationService::class)->send(
                $contactMessage->email,
                $validated['subject'],
                'contact-reply',
                [
                    'recipient_name'    => $contactMessage->name,
                    'reply_body'        => $validated['body'],
                    'original_excerpt'  => \Illuminate\Support\Str::limit($contactMessage->message, 300),
                    'subject'           => $validated['subject'],
                ]
            );
        } catch (\Throwable $e) {
            \Log::error('Contact reply failed', [
                'message_id' => $contactMessage->id,
                'error'      => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Failed to send reply. Please try again.',
            ], 500);
        }

        // Mark the original as read
        if (! $contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }

        \App\Support\AuditContext::wrap('reply_contact_message', function () use ($contactMessage, $validated) {
            // Audit context is captured around the update above; this block is for the reply metadata.
        }, [
            'message_id' => $contactMessage->id,
            'subject'    => $validated['subject'],
        ]);

        return response()->json([
            'message' => 'Reply sent to ' . $contactMessage->email,
        ]);
    }
}