<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\StoreConversationRequest;
use App\Models\Conversation;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\ClassRoom;

class MessageController extends Controller
{
    /**
     * List my conversations, newest first.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->with(['participants:id,name', 'latestMessage'])
            ->orderByDesc('updated_at')
            ->get()
            ->map(function (Conversation $conv) use ($user) {
                $other = $conv->participants->firstWhere('id', '!=', $user->id);
                $lastRead = $conv->participants
                    ->firstWhere('id', $user->id)?->pivot?->last_read_at;

                $unread = $conv->messages()
                    ->where('sender_id', '!=', $user->id)
                    ->when($lastRead, fn ($q) => $q->where('created_at', '>', $lastRead))
                    ->count();

                $lastMessage = $conv->latestMessage;

                return [
                    'id'        => $conv->id,
                    'subject'   => $conv->subject,
                    'participant' => $other ? [
                        'id'   => $other->id,
                        'name' => $other->name,
                    ] : null,
                    'last_message' => $lastMessage ? [
                        'body'     => \Str::limit($lastMessage->body, 80),
                        'sent_at'  => $lastMessage->created_at->toIso8601String(),
                        'sent_human' => $lastMessage->created_at->diffForHumans(),
                        'is_mine'  => $lastMessage->sender_id === $user->id,
                    ] : null,
                    'unread_count' => $unread,
                    'updated_at'   => $conv->updated_at->toIso8601String(),
                ];
            });

        $payload = ['conversations' => $conversations];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Messages/Index', $payload);
    }

    /**
     * Show the recipient picker.
     */
    public function create(Request $request)
    {
        $recipients = $this->eligibleRecipients($request->user());

        $payload = [
            'recipients' => $recipients->map(fn (User $u) => [
                'id'    => $u->id,
                'name'  => $u->name,
                'email' => $u->email,
                'role'  => $u->getRoleNames()->first(),
            ]),
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Messages/Create', $payload);
    }

    /**
     * Start a new conversation.
     */
    public function store(StoreConversationRequest $request)
    {
        $user = $request->user();
        $recipientId = (int) $request->validated('recipient_id');

        // Security: recipient must be in the eligible list
        $eligible = $this->eligibleRecipients($user)->pluck('id')->all();
        if (! in_array($recipientId, $eligible, true)) {
            return $request->wantsJson()
                ? response()->json(['message' => 'You cannot message this user.'], 403)
                : back()->with('error', 'You cannot message this user.');
        }

        $conversation = DB::transaction(function () use ($user, $recipientId, $request) {
            $conv = Conversation::create([
                'subject' => $request->validated('subject') ?: null,
            ]);

            $conv->participants()->attach([
                $user->id        => ['last_read_at' => now()],
                $recipientId     => ['last_read_at' => null],
            ]);

            $conv->messages()->create([
                'sender_id' => $user->id,
                'body'      => $request->validated('body'),
            ]);

            return $conv;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message'      => 'Conversation started.',
                'conversation' => $conversation->load('participants:id,name'),
            ], 201);
        }

        return redirect()->route('messages.show', $conversation->id);
    }

    /**
     * View one conversation + its messages. Marks as read.
     */
    public function show(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        abort_unless(
            $conversation->participants()->where('user_id', $user->id)->exists(),
            403
        );

        $conversation->load(['participants:id,name', 'messages.sender:id,name']);

        // Mark as read for the current user
        DB::table('conversation_participants')
            ->where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->update(['last_read_at' => now()]);

        $other = $conversation->participants->firstWhere('id', '!=', $user->id);

        $payload = [
            'conversation' => [
                'id'      => $conversation->id,
                'subject' => $conversation->subject,
                'participant' => $other ? [
                    'id'   => $other->id,
                    'name' => $other->name,
                ] : null,
            ],
            'messages' => $conversation->messages->map(fn ($m) => [
                'id'         => $m->id,
                'body'       => $m->body,
                'is_mine'    => $m->sender_id === $user->id,
                'sender'     => $m->sender?->name,
                'sent_at'    => $m->created_at->toIso8601String(),
                'sent_human' => $m->created_at->diffForHumans(),
            ]),
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Messages/Show', $payload);
    }

    /**
     * Send a message in an existing conversation.
     */
    public function reply(SendMessageRequest $request, Conversation $conversation)
    {
        $user = $request->user();

        abort_unless(
            $conversation->participants()->where('user_id', $user->id)->exists(),
            403
        );

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body'      => $request->validated('body'),
        ]);

        // Touch updated_at so it floats to top of list
        $conversation->touch();

        // Mark as read for the sender (they know what they sent)
        DB::table('conversation_participants')
            ->where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->update(['last_read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Message sent.',
                'data'    => [
                    'id'      => $message->id,
                    'body'    => $message->body,
                    'sent_at' => $message->created_at->toIso8601String(),
                ],
            ], 201);
        }

        return back();
    }

    // ──────────────────────────────────────────────────
    // Recipient eligibility by role
    // ──────────────────────────────────────────────────

    protected function eligibleRecipients(User $user)
    {
        $ids = collect();

        if ($user->hasRole('teacher') && $user->teacher) {
            // All students in classroom this teacher handles
            $studentUserIds = Student::query()
                ->whereHas('classroom', fn ($q) => $q->where('teacher_id', $user->teacher->id))
                ->pluck('user_id');

            // All other teachers
            $otherTeacherIds = Teacher::query()
                ->where('id', '!=', $user->teacher->id)
                ->pluck('user_id');

            // Admins
            $adminIds = User::role('admin')->pluck('id');

            $ids = $studentUserIds->merge($otherTeacherIds)->merge($adminIds);
        } elseif ($user->hasRole('student') && $user->student) {
            // Teachers of my classes
            $teacherUserIds = Teacher::query()
                ->whereHas('classroom.students', function ($q) use ($user) {
                    $q->where('students.id', $user->student->id);
                })
                ->pluck('user_id');

            // Classmates via shared sections
            $classroomIds = $user->student->classroom()->pluck('classes.id');
            $sectionIds = ClassRoom::whereIn('id', $classroomIds)
                ->pluck('section_id')
                ->unique();

            $classmateUserIds = Student::query()
                ->where('id', '!=', $user->student->id)
                ->whereHas('enrollments', function ($q) use ($sectionIds) {
                    $q->whereIn('section_id', $sectionIds)
                    ->where('status', 'enrolled');
                })
                ->pluck('user_id');

            $adminIds = User::role('admin')->pluck('id');

            $ids = $teacherUserIds->merge($classmateUserIds)->merge($adminIds);
        } elseif ($user->hasRole('admin')) {
            // Admin can message anyone
            $ids = User::where('id', '!=', $user->id)->pluck('id');
        }

        // Never include self; dedupe; keep only existing users
        return User::query()
            ->whereIn('id', $ids->unique()->diff([$user->id]))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }
}