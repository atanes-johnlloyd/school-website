<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20)
            ->through(fn ($n) => [
                'id'         => $n->id,
                'title'      => $n->data['title'] ?? 'Notification',
                'body'       => $n->data['body'] ?? '',
                'url'        => $n->data['url'] ?? null,
                'kind'       => $n->data['kind'] ?? 'info',
                'read_at'    => $n->read_at?->toIso8601String(),
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return Inertia::render('Notifications/Index', [
            'items' => $items,
        ]);
    }

    public function markRead(Request $request, string $id)
    {
        $n = $request->user()->notifications()->where('id', $id)->firstOrFail();
        $n->markAsRead();

        return back();
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }
}