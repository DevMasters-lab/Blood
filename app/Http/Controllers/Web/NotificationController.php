<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    // =========================================================================
    // 1. API: Fetch notifications for the Alpine.js dropdown
    // =========================================================================
    public function fetchDynamic(Request $request)
    {
        $notifications = collect();
        $unreadCount = 0;

        // 1. CHECK IF IT IS AN ADMIN
        if (auth('admin')->check()) {
            $notifications = auth('admin')->user()->notifications()->latest()->take(20)->get();
            $unreadCount = auth('admin')->user()->unreadNotifications()->count();
        } 
        // 2. CHECK IF IT IS A LOGGED-IN USER
        elseif (auth('web')->check()) {
            $notifications = auth('web')->user()->notifications()->latest()->take(20)->get();
            $unreadCount = auth('web')->user()->unreadNotifications()->count();
        }
        
        // 🌟 FIX: If they are NOT logged in, we do nothing! 
        // The API will safely return 0 notifications for guests.

        // Format for the frontend
        $formatted = $notifications->map(function ($notif) {
            return [
                'id' => $notif->id,
                'title' => $notif->data['title'] ?? 'Alert',
                'message' => $notif->data['message'] ?? '',
                'icon' => $notif->data['icon'] ?? 'fa-bell',
                'time' => $notif->created_at->diffForHumans(),
                'is_read' => $notif->read_at !== null,
            ];
        });

        return response()->json([
            'count' => $unreadCount,
            'notifications' => $formatted
        ]);
    }

    // =========================================================================
    // 2. READ & REDIRECT: When someone clicks a specific notification
    // =========================================================================
    public function readAndRedirect($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? url()->previous());
    }

    // =========================================================================
    // 3. API: Mark all as read dynamically (Alpine.js)
    // =========================================================================
    public function markAllDynamic(Request $request)
    {
        if (auth('admin')->check()) {
            auth('admin')->user()->unreadNotifications->markAsRead();
        } elseif (auth('web')->check()) {
            auth('web')->user()->unreadNotifications->markAsRead();
        }

        return response()->json(['status' => 'success']);
    }

    // =========================================================================
    // 4. OLD BLADE METHOD: Mark all as read (Fallback)
    // =========================================================================
    public function markAllAsRead()
    {
        if (auth('admin')->check()) {
            auth('admin')->user()->unreadNotifications->markAsRead();
        } elseif (auth('web')->check()) {
            auth('web')->user()->unreadNotifications->markAsRead();
        }
        
        return back()->with('success', 'All notifications marked as read.');
    }
}