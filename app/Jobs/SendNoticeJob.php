<?php

namespace App\Jobs;

use App\Models\Notice;
use App\Models\User;
use App\Notifications\NoticePublishedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNoticeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $noticeId;

    public function __construct(int $noticeId)
    {
        $this->noticeId = $noticeId;
    }

    public function handle(): void
    {
        $notice = Notice::find($this->noticeId);

        if (!$notice) {
            Log::warning("Notice ID {$this->noticeId} not found.");
            return;
        }

        Log::info("SendNoticeJob started Notice ID: {$notice->id}");

        $notice->update(['status' => 'active']);

        $users = match ($notice->target_role) {
            'student' => $this->getTargetStudents($notice),
            'teacher' => User::where('role', 'teacher')->get(),
            'admin'   => User::where('role', 'admin')->get(),
            'all'     => User::all(),
            default   => collect(),
        };

        foreach ($users as $user) {
            $user->notify(new NoticePublishedNotification($notice));
        }

        Log::info("Notice ID {$notice->id} active and {$users->count()} user get notified.");
    }

    protected function getTargetStudents(Notice $notice)
    {
        if ($notice->target_course_id) {
            return User::whereHas('orders.orderItems', function ($q) use ($notice) {
                $q->where('course_id', $notice->target_course_id)
                ->whereHas('order', function ($query) {
                    $query->where('status', 'approved');
                });
            })->get();
        }

        return User::where('role', 'student')->get();
    }
}
