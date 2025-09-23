<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notice;
use App\Jobs\SendNoticeJob;
use Illuminate\Support\Facades\Log;

class CheckNotices extends Command
{
    protected $signature = 'notices:check';
    protected $description = 'Check schedule notices and dispatch jobs';

    public function handle()
    {
        try {
            $notices = Notice::where('status', 'schedule')
                             ->where('start_at', '<=', now())
                             ->get();

            foreach ($notices as $notice) {
                SendNoticeJob::dispatch($notice);
            }

            // Active notices expired end_at cross
            $expiredNotices = Notice::where('status', 'active')
                                    ->whereNotNull('end_at')
                                    ->where('end_at', '<', now())
                                    ->get();

            foreach ($expiredNotices as $notice) {
                $notice->update(['status' => 'inactive']);
                Log::info("Notice ID {$notice->id} has expired. Status changed to inactive.");
            }

            $this->info('Schedule notices checked, jobs dispatched, and expired notices updated.');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in CheckNotices command: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
