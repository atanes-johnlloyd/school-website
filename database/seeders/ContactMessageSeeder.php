<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            ['Juan Dela Cruz',   'juan.delacruz@example.com',   'Inquiry about enrollment requirements',  'Good day! I would like to ask about the requirements for incoming Grade 11 students for S.Y. 2026-2027. Thank you.', false],
            ['Maria Santos',     'maria.santos@example.com',    'Request for Transcript of Records',      'Hello, I would like to request a copy of my TOR. What is the process and are there any fees? Thank you.', true],
            ['Pedro Reyes',      'pedro.reyes@example.com',     'Schedule of entrance exam',              'When is the next entrance exam for transferees? My son is from another division and would like to transfer.', false],
            ['Ana Villanueva',   'ana.villanueva@example.com',  'Inquiry on SHS Voucher coverage',        'Is the SHS Voucher Program still available for private school completers this year? How do we apply?', true],
            ['Rogelio Bautista', 'rogelio.b@example.com',       'Request for school calendar',            'Please send me a copy of the school calendar for SY 2026-2027 including holidays and exam weeks.', false],
            ['Luzviminda Tan',   'luz.tan@example.com',         'Concern about student portal access',    'My daughter cannot log in to her student portal. Password reset is not working. Please advise.', false],
            ['Carlo Mendoza',    'carlo.m@example.com',         'Partnership inquiry — industry linkage', 'We are a local IT company interested in offering internship slots for ICT strand students. Who can we coordinate with?', true],
        ];

        foreach ($messages as [$name, $email, $subject, $body, $read]) {
            ContactMessage::create([
                'name'    => $name,
                'email'   => $email,
                'subject' => $subject,
                'message' => $body,
                'is_read' => $read,
            ]);
        }

        $this->command->info('✅ Contact messages seeded: ' . count($messages));
    }
}