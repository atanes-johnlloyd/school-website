<?php

namespace Database\Seeders;

use App\Models\ClassModule;
use App\Models\ClassRoom;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use Illuminate\Database\Seeder;

class ClassContentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = ClassRoom::with('subject')->take(12)->get();
        $modulesPerClass = 2;
        $lessonsPerModule = 3;

        $moduleCount = 0;
        $lessonCount = 0;
        $attachCount = 0;

        foreach ($classes as $class) {
            $subjectName = $class->subject->name ?? 'Course';

            for ($m = 1; $m <= $modulesPerClass; $m++) {
                $module = ClassModule::create([
                    'class_id'     => $class->id,
                    'title'        => "Module {$m}: " . ($m === 1 ? 'Foundations' : 'Applications') . " of {$subjectName}",
                    'description'  => "This module covers key concepts, worked examples, and formative activities for {$subjectName}.",
                    'position'     => $m - 1,
                    'is_published' => true,
                ]);
                $moduleCount++;

                for ($l = 1; $l <= $lessonsPerModule; $l++) {
                    $lesson = Lesson::create([
                        'class_id'        => $class->id,
                        'class_module_id' => $module->id,
                        'title'           => "Lesson {$m}.{$l} — " . $this->lessonTitle($l),
                        'body'            => "In this lesson, we will explore the key ideas behind " .
                                             $this->lessonTopic($l) . ". Read the attached handout and " .
                                             "complete the practice items at the end. Expect a short check-up quiz in the next session.",
                        'position'        => $l - 1,
                        'is_published'    => true,
                    ]);
                    $lessonCount++;

                    LessonAttachment::create([
                        'lesson_id'  => $lesson->id,
                        'file_path'  => "lessons/{$class->id}/l{$lesson->id}-handout.pdf",
                        'file_name'  => "Lesson_{$m}_{$l}_Handout.pdf",
                        'mime_type'  => 'application/pdf',
                        'file_size'  => rand(120_000, 2_400_000),
                    ]);
                    $attachCount++;

                    LessonAttachment::create([
                        'lesson_id'  => $lesson->id,
                        'file_path'  => "lessons/{$class->id}/l{$lesson->id}-slides.pptx",
                        'file_name'  => "Lesson_{$m}_{$l}_Slides.pptx",
                        'mime_type'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'file_size'  => rand(500_000, 8_000_000),
                    ]);
                    $attachCount++;
                }
            }
        }

        $this->command->info("✅ Class content seeded: {$moduleCount} modules, {$lessonCount} lessons, {$attachCount} attachments");
    }

    private function lessonTitle(int $n): string
    {
        return match ($n) {
            1 => 'Introduction and Vocabulary',
            2 => 'Core Concepts and Examples',
            default => 'Practice and Application',
        };
    }

    private function lessonTopic(int $n): string
    {
        return match ($n) {
            1 => 'the foundational terminology',
            2 => 'the underlying principles',
            default => 'real-world problem solving',
        };
    }
}