<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Get all notifications for current user
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    // Get unread count (for badge)
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    // Get recent notifications (for dropdown)
    public function getRecent()
    {
        $notifications = Auth::user()->notifications()
            ->recent(10)
            ->get();

        return response()->json($notifications);
    }

    // Mark notification as read
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        if ($notification->link) {
            return redirect($notification->link);
        }

        return redirect()->back()->with('success', 'Đã đánh dấu đã đọc');
    }

    // Mark all as read
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return redirect()->back()->with('success', 'Đã đánh dấu tất cả là đã đọc');
    }

    // Delete notification
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return redirect()->back()->with('success', 'Đã xóa thông báo');
    }

    // Delete all read notifications
    public function clearRead()
    {
        Auth::user()->notifications()
            ->where('is_read', true)
            ->delete();

        return redirect()->back()->with('success', 'Đã xóa thông báo đã đọc');
    }
}
