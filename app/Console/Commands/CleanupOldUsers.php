<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CleanupOldUsers extends Command
{
    protected $signature = 'users:cleanup {--days=1}';
    protected $description = 'Delete users inactive for more than X days';

    public function handle(): void
    {
        $days = $this->option('days');
        $cutoff = now()->subDays($days);
        $count = User::where('created_at', '<', $cutoff)->delete();
        $this->info("Deleted {$count} inactive users older than {$days} days.");
    }
}
