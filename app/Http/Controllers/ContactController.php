<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Public — submit a contact message. No auth.
     */
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

    /**
     * Admin — inbox list.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'filter'   => ['nullable', 'in:all,unread,read'],
            'search'   => ['nullable', 'string', 'max:200'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $query = ContactMessage::query()->latest();

        $filter = $validated['filter'] ?? 'all';
        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('subject', 'like', "%{$s}%")
                  ->orWhere('message', 'like', "%{$s}%");
            });
        }

        $messages = $query->paginate($validated['per_page'] ?? 20);

        $payload = [
            'messages'    => $messages,
            'unread_count'=> ContactMessage::unread()->count(),
            'filters'     => [
                'filter' => $filter,
                'search' => $validated['search'] ?? null,
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Admin/ContactMessages/Index', $payload);
    }

    /**
     * Admin — view one message (and mark read).
     */
    public function show(Request $request, ContactMessage $contactMessage)
    {
        if (! $contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }

        $payload = ['contact_message' => $contactMessage];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Admin/ContactMessages/Show', $payload);
    }

    /**
     * Admin — toggle read/unread.
     */
    public function toggleRead(Request $request, ContactMessage $contactMessage)
    {
        $contactMessage->update(['is_read' => ! $contactMessage->is_read]);

        return response()->json([
            'message' => $contactMessage->is_read ? 'Marked as read.' : 'Marked as unread.',
            'is_read' => $contactMessage->is_read,
        ]);
    }

    /**
     * Admin — delete.
     */
    public function destroy(Request $request, ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return response()->json(['message' => 'Message deleted.']);
    }
}