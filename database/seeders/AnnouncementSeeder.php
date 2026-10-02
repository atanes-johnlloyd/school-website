<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'sysadmin@test.com')->first()
              ?? User::where('email', 'atanes.johnlloyd@ncst.edu.ph')->first();

        // ── School-wide ─────────────────────────────────────────
        $schoolWide = [
            ['Enrollment for S.Y. 2026–2027 is Now Open',
             'Enrollment runs until June 30, 2026. Incoming Grade 11 students should bring their Form 138, PSA birth certificate, and 2x2 ID photos. Walk-in applicants are welcome at the Registrar\'s Office from 8 AM to 4 PM.',
             true, 'urgent', -2, 45],
            ['First Quarter Examination Schedule Released',
             'The quarterly exams will be held from October 12–16, 2026. Please coordinate with your class advisers for room assignments. Bring your own pens, calculators (for Math/Science), and school ID.',
             true, 'important', -5, 30],
            ['No Classes on August 21 (Ninoy Aquino Day)',
             'Malacañang has declared August 21, 2026 a regular holiday. No synchronous or asynchronous classes will take place. Regular schedule resumes August 22.',
             false, 'normal', -1, 20],
            ['Brigada Eskwela 2026: Bayanihan sa Paaralan',
             'Join us on August 1–5, 2026 for the annual Brigada Eskwela. Parents, alumni, and community volunteers are welcome. Bring your own cleaning materials if possible.',
             false, 'important', -10, 15],
            ['Senior High Voucher Program Orientation',
             'Grade 10 completers and transferees are invited to a voucher orientation on July 5, 2026, 9 AM at the school gymnasium. Discusses DepEd SHS-VP coverage and requirements.',
             false, 'normal', -7, 25],
        ];

        foreach ($schoolWide as [$title, $body, $pinned, $priority, $pubDays, $expDays]) {
            Announcement::create([
                'created_by'     => $admin->id,
                'class_id'       => null,
                'title'          => $title,
                'body'           => $body,
                'is_pinned'      => $pinned,
                'is_school_wide' => true,
                'priority'       => $priority,
                'published_at'   => now()->addDays($pubDays),
                'expires_at'     => now()->addDays($expDays),
            ]);
        }

        // ── Per-class announcements (teacher-authored) ──────────
        $teachers = User::role('teacher')->take(6)->get();
        $classes  = ClassRoom::with('subject')->take(12)->get();

        foreach ($classes as $i => $class) {
            $author = $teachers[$i % $teachers->count()] ?? $admin;

            Announcement::create([
                'created_by'     => $author->id,
                'class_id'       => $class->id,
                'title'          => 'Welcome to ' . ($class->subject->name ?? 'our class'),
                'body'           => "Please review the course outline and prepare your notebooks for our first session. All activities, quizzes, and assignments will be posted here. Let's have a productive semester!",
                'is_pinned'      => true,
                'is_school_wide' => false,
                'priority'       => 'normal',
                'published_at'   => now()->subDays(6),
                'expires_at'     => now()->addDays(60),
            ]);

            if ($i % 2 === 0) {
                Announcement::create([
                    'created_by'     => $author->id,
                    'class_id'       => $class->id,
                    'title'          => 'Reminder: Submit Pending Requirements',
                    'body'           => 'A few of you still have missing submissions. Please coordinate with me during consultation hours or send a message through the LMS.',
                    'is_pinned'      => false,
                    'is_school_wide' => false,
                    'priority'       => 'important',
                    'published_at'   => now()->subDays(2),
                    'expires_at'     => now()->addDays(14),
                ]);
            }
        }

        $this->command->info('✅ Announcements seeded: '
            . count($schoolWide) . ' school-wide + '
            . ($classes->count() + intdiv($classes->count(), 2)) . ' class-scoped');
    }
}