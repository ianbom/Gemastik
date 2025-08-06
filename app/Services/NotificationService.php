<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Container\Attributes\Auth;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function readNotification($id){
        $notification = Notification::where('id', $id)
                ->where('user_id', Auth::id()) // Pastikan user hanya bisa update notifikasi miliknya
                ->firstOrFail();

        $notification->update(['is_read' => true]);
    }

    public function readAllNotification(){

        $notifications = Notification::where('user_id', Auth::id())->where('is_read', false)->get();
        $notifications->update(['is_read', true]);
    }
}
