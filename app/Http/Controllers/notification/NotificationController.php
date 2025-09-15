<?php

namespace App\Http\Controllers\notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notifications.index');
    }

    // Fetch notifications with pagination
    public function fetchNotifications(Request $request)
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);

        if ($request->ajax()) {
            $view = view('notifications.partials.notification_list', compact('notifications'))->render();
            return response()->json([
                'html' => $view,
            ]);
        }

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read successfully!' 
        ]);
    }
}
