<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupLeadBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:cleanup-backups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up lead backups older than 40 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up lead backups older than 40 days...');
        
        $deleted = DB::table('lead_backups')
            ->where('expires_at', '<', now())
            ->delete();
        
        $this->info("Deleted {$deleted} expired lead backup(s).");
        
        return Command::SUCCESS;
    }
}
