<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Mengirimkan notifikasi baru (trigger secara manual)
    public function sendNotification(Request $request)
    {
        $user = Auth::user();

        $user->notify(new \App\Notifications\LiveNotification($request->message));

        return response()->json(['status' => 'Notification sent']);
    }

    // Endpoint SSE untuk streaming notifikasi
    public function streamNotifications()
    {
        return response()->stream(function () {
            while (true) {
                $notifications = Auth::user()->unreadNotifications;

                echo "data: " . json_encode($notifications) . "\n\n";
                ob_flush();
                flush();

                // Periksa setiap 5 detik
                sleep(5);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    // Tandai notifikasi sebagai dibaca
    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'All notifications marked as read']);
    }
}
