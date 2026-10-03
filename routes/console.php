<?php

use App\Console\Commands\PruneAuditLogs;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Scheduled Commands
|--------------------------------------------------------------------------
*/

// Prune audit logs older than 365 days, on the 1st of every month at 03:00.
Schedule::command('audit-logs:prune', ['--days' => 365])
    ->monthlyOn(1, '03:00')
    ->onOneServer()
    ->withoutOverlapping();

// ── Add any future scheduled commands below ──
// e.g. Schedule::command('exam-results:mark-absent')->dailyAt('02:00');