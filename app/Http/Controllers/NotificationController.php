<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($request->expectsJson()) {
            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $notifications->items(),
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
            ]);
        }

        return view('notifications.index');
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $unreadCount = Auth::user()->notifications()->whereNull('read_at')->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get all unread notifications
     */
    public function getUnread()
    {
        $user = Auth::user();
        $unreadNotifications = $user->notifications()
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $unreadNotifications,
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found.',
            ], 404);
        }

        $notification->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * Delete a notification
     */
    public function delete($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found.',
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted.',
        ]);
    }

    /**
     * Delete all notifications
     */
    public function deleteAll()
    {
        $user = Auth::user();
        $user->notifications()->delete();

        return response()->json([
            'success' => true,
            'message' => 'All notifications deleted.',
        ]);
    }
}
