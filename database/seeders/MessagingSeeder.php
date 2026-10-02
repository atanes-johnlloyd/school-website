<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessagingSeeder extends Seeder
{
    public function run(): void
    {
        $admin   = User::where('email', 'sysadmin@test.com')->first();
        $teacher = User::where('email', 'teacher@test.com')->first();
        $student = User::where('email', 'student@test.com')->first();

        if (! $admin || ! $teacher || ! $student) {
            $this->command->warn('⚠ Messaging: required test users missing — skipping.');
            return;
        }

        // Conversation 1: teacher ↔ student
        $c1 = Conversation::create(['subject' => 'Question about the upcoming quarterly exam']);
        foreach ([$teacher, $student] as $u) {
            ConversationParticipant::create([
                'conversation_id' => $c1->id,
                'user_id'         => $u->id,
                'last_read_at'    => now()->subHour(),
            ]);
        }
        $this->say($c1, $student, 'Ma\'am, will the quarterly exam cover all 5 modules or just the last two?', -180);
        $this->say($c1, $teacher, 'It covers Modules 1–5. I uploaded a reviewer under Lessons. Please review it this weekend.', -175);
        $this->say($c1, $student, 'Thank you, Ma\'am! I\'ll review it tonight. 🙏', -170);

        // Conversation 2: admin ↔ teacher
        $c2 = Conversation::create(['subject' => 'Submission of Q1 grade sheets']);
        foreach ([$admin, $teacher] as $u) {
            ConversationParticipant::create([
                'conversation_id' => $c2->id,
                'user_id'         => $u->id,
                'last_read_at'    => now()->subMinutes(30),
            ]);
        }
        $this->say($c2, $admin, 'Hi Teacher, reminder to submit your Q1 grade sheets by Friday EOD.', -300);
        $this->say($c2, $teacher, 'Noted. I\'m done with two sections; the third is still being finalized.', -290);
        $this->say($c2, $admin, 'Perfect. Attach the PDF in the Gradebook export when ready.', -285);

        // Conversation 3: teacher ↔ another teacher
        $other = User::role('teacher')->where('id', '!=', $teacher->id)->first();
        if ($other) {
            $c3 = Conversation::create(['subject' => 'Inter-class quiz collaboration']);
            foreach ([$teacher, $other] as $u) {
                ConversationParticipant::create([
                    'conversation_id' => $c3->id,
                    'user_id'         => $u->id,
                ]);
            }
            $this->say($c3, $teacher, 'Would you like to co-develop a 20-item formative quiz for the STEM sections?', -90);
            $this->say($c3, $other,   'Sure! Let\'s sync next Wednesday during free period.', -85);
        }

        $this->command->info('✅ Messaging seeded: 3 conversations with message threads');
    }

    private function say(Conversation $c, User $sender, string $body, int $minutesAgo): void
    {
        Message::create([
            'conversation_id' => $c->id,
            'sender_id'       => $sender->id,
            'body'            => $body,
            'created_at'      => now()->addMinutes($minutesAgo),
            'updated_at'      => now()->addMinutes($minutesAgo),
        ]);
    }
}