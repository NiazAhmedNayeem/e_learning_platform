<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear';
    protected $description = 'Clear all Laravel log files';

    public function handle()
    {
        $files = File::files(storage_path('logs'));
        foreach ($files as $file) {
            File::delete($file);
        }
        $this->info('All logs cleared successfully!');
    }
}
