<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class PruneAuditLogs extends Command
{
    protected $signature = 'audit-logs:prune
                            {--days=365 : Delete logs older than this many days}
                            {--dry-run : Report what would be deleted without deleting}';

    protected $description = 'Delete audit log entries older than the retention window.';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $query = AuditLog::where('created_at', '<', $cutoff);
        $count = $query->count();

        if ($count === 0) {
            $this->info("No audit log entries older than {$days} days.");
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->warn("[dry-run] Would delete {$count} entries older than {$cutoff->toDateTimeString()}.");
            return self::SUCCESS;
        }

        // Delete in chunks to avoid long-running transactions on huge tables.
        $deleted = 0;
        $query->orderBy('id')->chunkById(1000, function ($rows) use (&$deleted) {
            $ids = $rows->pluck('id');
            $deleted += AuditLog::whereIn('id', $ids)->delete();
        });

        $this->info("Pruned {$deleted} audit log entries older than {$cutoff->toDateString()}.");
        return self::SUCCESS;
    }
}