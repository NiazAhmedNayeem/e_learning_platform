<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notice;
use App\Jobs\SendNoticeJob;

class CheckNotices extends Command
{
    protected $signature = 'notices:check';
    protected $description = 'Check inactive notices and dispatch jobs';

    public function handle()
    {
        $notices = Notice::where('status', 'inactive')
                         ->where('start_at', '<=', now())
                         ->get();

        foreach ($notices as $notice) {
            SendNoticeJob::dispatch($notice);
        }

        $this->info('Inactive notices checked and jobs dispatched.');
    }
}
