<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class MessageDrawerController extends Controller
{
    public function list(Request $request)
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->with(['participants:id,name,avatar_path', 'latestMessage.sender:id,name'])
            ->orderByDesc('updated_at')
            ->limit(50)
            ->get()
            ->map(function (Conversation $c) use ($user) {
                $other = $c->participants->firstWhere('id', '!=', $user->id);
                $pivot = $c->participants->firstWhere('id', $user->id)?->pivot;
                $lastRead = $pivot?->last_read_at;

                $unread = $c->messages()
                    ->where('sender_id', '!=', $user->id)
                    ->when($lastRead, fn ($q) => $q->where('created_at', '>', $lastRead))
                    ->count();

                return [
                    'id'             => $c->id,
                    'subject'        => $c->subject,
                    'other_user'     => $other ? [
                        'id'         => $other->id,
                        'name'       => $other->name,
                        'avatar_url' => $other->avatar_url,
                    ] : null,
                    'latest_message' => $c->latestMessage ? [
                        'body'          => \Str::limit($c->latestMessage->body, 80),
                        'is_mine'       => $c->latestMessage->sender_id === $user->id,
                        'created_human' => $c->latestMessage->created_at?->diffForHumans(),
                    ] : null,
                    'unread_count'   => $unread,
                ];
            });

        return response()->json(['conversations' => $conversations]);
    }

    public function show(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        abort_unless($conversation->participants()->where('users.id', $user->id)->exists(), 403);

        $conversation->participants()->updateExistingPivot($user->id, ['last_read_at' => now()]);

        $messages = $conversation->messages()
            ->with('sender:id,name,avatar_path')
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => [
                'id'            => $m->id,
                'body'          => $m->body,
                'is_mine'       => $m->sender_id === $user->id,
                'sender'        => [
                    'id'         => $m->sender?->id,
                    'name'       => $m->sender?->name,
                    'avatar_url' => $m->sender?->avatar_url,
                ],
                'created_human' => $m->created_at?->diffForHumans(),
            ]);

        $conversation->load('participants:id,name,avatar_path');
        $other = $conversation->participants->firstWhere('id', '!=', $user->id);

        return response()->json([
            'conversation' => [
                'id'         => $conversation->id,
                'subject'    => $conversation->subject,
                'other_user' => $other ? [
                    'id'         => $other->id,
                    'name'       => $other->name,
                    'avatar_url' => $other->avatar_url,
                ] : null,
            ],
            'messages' => $messages,
        ]);
    }

    public function reply(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        abort_unless($conversation->participants()->where('users.id', $user->id)->exists(), 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body'      => $validated['body'],
        ]);

        $conversation->touch();

        return response()->json([
            'message' => [
                'id'            => $message->id,
                'body'          => $message->body,
                'is_mine'       => true,
                'sender'        => ['id' => $user->id, 'name' => $user->name, 'avatar_url' => $user->avatar_url],
                'created_human' => $message->created_at?->diffForHumans(),
            ],
        ], 201);
    }

    public function start(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'body'    => ['required', 'string', 'max:5000'],
        ]);

        abort_if($validated['user_id'] === $user->id, 422, 'Cannot message yourself.');

        $existing = Conversation::whereHas('participants', fn ($q) => $q->where('users.id', $user->id))
            ->whereHas('participants', fn ($q) => $q->where('users.id', $validated['user_id']))
            ->has('participants', '=', 2)
            ->first();

        if ($existing) {
            $message = $existing->messages()->create([
                'sender_id' => $user->id,
                'body'      => $validated['body'],
            ]);
            $existing->touch();

            return response()->json([
                'conversation_id' => $existing->id,
                'message_id'      => $message->id,
            ], 201);
        }

        $conversation = Conversation::create(['subject' => 'Direct message']);

        $conversation->participants()->attach([
            $user->id              => ['last_read_at' => now()],
            $validated['user_id']  => ['last_read_at' => null],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body'      => $validated['body'],
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'message_id'      => $message->id,
        ], 201);
    }

    public function users(Request $request)
    {
        $user = $request->user();
        $q    = trim((string) $request->input('q', ''));

        $users = User::query()
            ->where('id', '!=', $user->id)
            ->when($q, function ($query) use ($q) {
                $query->where(fn ($sq) => $sq
                    ->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%"));
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'email', 'avatar_path'])
            ->map(fn ($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'avatar_url' => $u->avatar_url,
            ]);

        return response()->json(['users' => $users]);
    }
}