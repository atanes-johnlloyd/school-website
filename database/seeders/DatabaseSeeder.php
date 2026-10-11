<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * DatabaseSeeder
 * --------------
 * Entry point for `php artisan db:seed` and `migrate:fresh --seed`.
 *
 * Runs the 8 application seeders in dependency order. Optionally
 * truncates every application table first (FK-safe) so re-running
 * doesn't duplicate data.
 *
 * Seeder order (do not rearrange — each depends on the one before):
 *   1. ReferenceDataSeeder        — years, terms, tracks, clusters,
 *                                    subjects, rooms, positions, roles,
 *                                    permissions, settings
 *   2. UserAndStaffSeeder         — 5 admins + 18 teachers
 *   3. ApplicantSeeder            — 32 applicants + exams + results
 *   4. StudentConversionSeeder    — enrolled applicants → students,
 *                                    plus historical/current cohorts
 *   5. SectionAndEnrollmentSeeder — sections per year + enrollments
 *   6. ClassAndScheduleSeeder     — class records + weekly schedules
 *   7. ClassroomActivitySeeder    — grades, attendance, assignments,
 *                                    quizzes, announcements, messages
 *   8. ContributionAndPaymentSeeder — contributions, payments, webhooks
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Set to false if you want to append without wiping existing rows.
     * Default true so repeated dev runs stay clean.
     */
    protected bool $truncateBeforeSeeding = true;

    public function run(): void
    {
        if ($this->truncateBeforeSeeding) {
            $this->truncateAll();
        }

        $this->call([
            ReferenceDataSeeder::class,
            UserAndStaffSeeder::class,
            ApplicantSeeder::class,
            StudentConversionSeeder::class,
            SectionAndEnrollmentSeeder::class,
            ClassAndScheduleSeeder::class,
            ClassroomActivitySeeder::class,
            ContributionAndPaymentSeeder::class,
        ]);
    }

    /**
     * Truncate every application table in FK-safe fashion.
     *
     * Wraps the whole loop in disable/enable FK constraints so MySQL
     * permits TRUNCATE on parent tables (e.g., `terms` referenced by
     * `classes.term_id`).
     *
     * Excludes:
     *   • `migrations` — Laravel needs to know what's already applied
     *   • `sessions`   — active dev sessions (safe to skip)
     *   • `cache` / `cache_locks` — harmless to keep
     */
    protected function truncateAll(): void
    {
        // Capture the list of tables once, up front.
        $tables = collect(DB::select('SHOW TABLES'))
            ->map(fn ($row) => array_values((array) $row)[0])
            ->reject(fn ($t) => in_array($t, [
                'migrations',
                'sessions',
                'cache',
                'cache_locks',
                'jobs',
                'job_batches',
                'failed_jobs',
            ], true))
            ->values()
            ->all();

        Schema::disableForeignKeyConstraints();

        try {
            foreach ($tables as $table) {
                DB::table($table)->truncate();
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $this->command->info('✔ Truncated ' . count($tables) . ' tables.');
    }
}