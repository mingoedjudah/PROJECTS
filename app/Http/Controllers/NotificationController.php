<?php

namespace App\Http\Controllers;
use App\Models\Notification;
use App\Models\Like;
use App\Models\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\UserRegistration;
use App\Models\UserFollow;


class NotificationController extends Controller
{
 
    public function checkNotifications()
    {
        $userId = Auth::id();
        $notifications = Notification::where('user_id', $userId)
            ->with(['likedBy', 'commentBy', 'replyBy'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        $notificationCount = $notifications->where('status', 'unread')->count();
    
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'notificationCount' => $notificationCount,
            'newNotifications' => $notifications->filter(function($notification) {
                return $notification->status === 'unread';
            })->values(), 
        ]);
    }
    
    public function markAllAsRead()
    {
        $userId = Auth::id();
        Notification::where('user_id', $userId)
                    ->update(['status' => 'read']);
        
        return response()->json(['success' => true]);
    }

    
}
