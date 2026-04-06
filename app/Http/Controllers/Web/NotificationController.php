<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Marks a single notification as read and redirects the user to the relevant page
    public function readAndRedirect($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? url()->previous());
    }

    // Marks all notifications as read at once
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'All notifications marked as read.');
    }
}