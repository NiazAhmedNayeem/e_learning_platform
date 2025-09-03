<?php

namespace App\Http\Controllers\message;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::where('id', '!=', auth()->user()->id)->get();

        // Count unread messages from each user
        foreach ($users as $user) {
            $user->unread_count = Message::where('sender_id', $user->id)
                                    ->where('receiver_id', auth()->user()->id)
                                    ->where('is_read', false)
                                    ->count();
        }

        return view('message.index', compact('users'));
    }


    // Fetch messages with a specific user
    public function fetchMessages($userId)
    {
        // Fetch messages between auth user & $userId
        $messages = Message::where(function($q) use ($userId) {
                $q->where('sender_id', auth()->user()->id)
                ->where('receiver_id', $userId);
            })->orWhere(function($q) use ($userId) {
                $q->where('sender_id', $userId)
                ->where('receiver_id', auth()->user()->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark all received messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', auth()->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }


    // Send message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json($message);
    }

    public function unreadMessages()
    {
        // Only unread messages for logged-in user
        $unreadMessages = Message::where('receiver_id', auth()->id())
                            ->where('is_read', false)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('message.navbar_messages', compact('unreadMessages'));
    }


    
    // MessageController.php
    public function navbarUnreadMessages()
    {
        $unreadMessages = Message::where('receiver_id', auth()->id())
                            ->where('is_read', false)
                            ->orderBy('created_at','desc')
                            ->get()
                            ->groupBy('sender_id');

        $data = [];

        foreach($unreadMessages as $sender_id => $messages){
            $lastMessage = $messages->first();
            $count = $messages->count();

            $data[] = [
                'sender_id' => $sender_id,
                'sender_name' => $lastMessage->sender->name,
                'sender_image' => $lastMessage->sender->image_show,
                'message' => $count === 1 ? $lastMessage->message : "+{$count} new messages", //  show text or count
                'unread_count' => $count,
                'created_at' => $lastMessage->created_at->diffForHumans(),
            ];
        }

        return response()->json($data);
    }


    public function markRead(Request $request)
    {
        Message::where('sender_id', $request->sender_id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }



    public function sidebar()
    {
        $users = User::where('id', '!=', auth()->id()) // auth user hide
            ->get()
            ->map(function($user){
                // Last message between auth user & this user
                $lastMessage = Message::where(function($q) use($user) {
                        $q->where('sender_id', auth()->id())
                        ->where('receiver_id', $user->id);
                    })
                    ->orWhere(function($q) use($user) {
                        $q->where('sender_id', $user->id)
                        ->where('receiver_id', auth()->id());
                    })
                    ->latest()
                    ->first();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'is_super' => $user->is_super,
                    'image_show' => $user->image_show,
                    'unread_count' => Message::where('sender_id', $user->id)
                                            ->where('receiver_id', auth()->id())
                                            ->where('is_read', false)
                                            ->count(),
                    // Always last message
                    'displayText' => $lastMessage 
                        ? ($lastMessage->sender_id == auth()->id() ? "You: " : "") . $lastMessage->message 
                        : '',
                    'timeText' => $lastMessage ? $lastMessage->created_at->diffForHumans() : '',
                    'last_message_time' => $lastMessage ? $lastMessage->created_at : null
                ];
            });

        return response()->json($users);
    }


}
