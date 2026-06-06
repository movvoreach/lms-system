<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function dropdown(Request $request)
    {
        $notifications = $request->user()->unreadNotifications()->latest()->limit(8)->get();

        return response()->json([
            'count' => $request->user()->unreadNotifications()->count(),
            'html' => view('admin.partials.notification-items', compact('notifications'))->render(),
        ]);
    }

    public function markRead(Request $request, string $notification)
    {
        $item = $request->user()->notifications()->where('id', $notification)->firstOrFail();
        $item->markAsRead();

        return response()->json(['ok' => true]);
    }
}


