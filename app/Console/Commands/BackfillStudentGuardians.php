<?php

namespace App\Console\Commands;

use App\Models\Applicant;
use App\Models\Student;
use Illuminate\Console\Command;

class BackfillStudentGuardians extends Command
{
    protected $signature   = 'students:backfill-guardians';
    protected $description = 'Copy guardian info from linked applicant contacts to students.';

    public function handle(): int
    {
        $updated = 0;

        Student::query()
            ->whereNull('guardian_name')
            ->chunkById(100, function ($students) use (&$updated) {
                foreach ($students as $student) {
                    $applicant = Applicant::with('contacts')
                        ->where('converted_student_id', $student->id)
                        ->first();

                    if (! $applicant) continue;

                    $guardian = $applicant->contacts
                        ->sortBy(fn ($c) => match ($c->role) {
                            'guardian' => 0,
                            'father'   => 1,
                            'mother'   => 2,
                            default    => 9,
                        })
                        ->first(fn ($c) => filled($c->full_name));

                    if (! $guardian) continue;

                    $student->update([
                        'guardian_name'    => $guardian->full_name,
                        'guardian_contact' => $guardian->contact_number,
                    ]);

                    $updated++;
                }
            });

        $this->info("Backfilled guardian info for {$updated} student(s).");

        return self::SUCCESS;
    }
}