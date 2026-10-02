<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherProfileEnhancerSeeder extends Seeder
{
    public function run(): void
    {
        $bios = [
            'Passionate educator with over a decade of classroom experience. Believes every learner can succeed with the right support.',
            'Dedicated to making complex topics accessible through real-world examples and hands-on activities.',
            'Focused on building critical thinking and communication skills through inquiry-based learning.',
            'Advocate for inclusive education. Uses differentiated instruction to reach learners at every level.',
            'Committed to lifelong learning and continuously refining teaching practices through action research.',
            'Brings industry experience into the classroom, helping students connect theory to practice.',
            'Champions project-based learning and sees mistakes as the best teachers.',
            'Guides students to think like researchers — curious, methodical, and honest.',
            'Encourages creativity alongside rigor, especially in performance-based subjects.',
            'Patient, structured, and known for clear explanations that stick.',
            'Pushes learners to aim higher while keeping expectations realistic and supportive.',
            'Creates a warm classroom culture where students feel safe to ask and try.',
            'Bridges the gap between DepEd standards and the demands of higher education.',
            'Emphasizes values alongside content — respect, responsibility, and resilience.',
            'Inspires students through personal stories and mentorship beyond the classroom.',
            'Innovative, tech-savvy, and always experimenting with new teaching tools.',
        ];

        $teachers = Teacher::orderBy('id')->get();

        foreach ($teachers as $i => $teacher) {
            $teacher->update([
                'is_publicly_visible' => true,
                'photo_path'          => "teachers/avatar-{$teacher->id}.jpg",
                'bio'                 => $bios[$i % count($bios)],
            ]);
        }

        $this->command->info('✅ Teacher profiles enhanced: ' . $teachers->count() . ' bios + visibility flags');
    }
}