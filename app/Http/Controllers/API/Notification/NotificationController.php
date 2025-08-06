<?php

namespace App\Http\Controllers\API\Notification;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: User not authenticated'
            ], 401);
        }

        // Fetch unread notifications
        $notifications = $user->notifications->map(function ($notification) {
            if ($notification->read_at == null) {
                $read = false;
            }elseif ($notification->read_at){
                $read = true;
            }

            return [
                'id' => $notification->id,
                'message' => $notification->data['message'],
                'time' => $notification->created_at->diffForHumans(),
                'read' => $read,
            ];
        });

        if ($notifications->count() === 0) {
            return Helper::jsonResponse(true, 'No notifications found', 200, []);
        }

        return Helper::jsonResponse(true, 'Notifications retrieved successfully', 200, $notifications);
    }

    public function unreadIndex()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: User not authenticated'
            ], 401);
        }

        // Fetch unread notifications
        $unreadNotifications = $user->unreadNotifications->map(function ($notification) {
            return [
                'message' => $notification->data['message'],
                'time' => $notification->created_at->diffForHumans()
            ];
        });

        // Mark seen notifications as read
        $user->unreadNotifications->markAsRead();

        if ($unreadNotifications->count() === 0) {
            return Helper::jsonResponse(true, 'No notifications found', 200, []);
        }

        return Helper::jsonResponse(true, 'Notifications retrieved successfully', 200, $unreadNotifications);
    }


}
