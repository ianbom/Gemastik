<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function readNotification($id)
    {
        DB::beginTransaction();
        try {
            $notification = Notification::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $notification->update(['is_read' => true]);
            DB::commit();

            return redirect()->back()->with('success', 'Notifikasi berhasil ditandai sebagai dibaca');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menandai notifikasi sebagai dibaca');
        }
    }

    public function readAllNotification()
    {
        DB::beginTransaction();
        try {
            Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);


            DB::commit();
            return redirect()->back()->with([
                'message' => 'Semua notifikasi berhasil ditandai sebagai dibaca.'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menandai notifikasi sebagai dibaca');
        }
    }

    public function destroy($id)
    {

        DB::beginTransaction();
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();
            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back();
        }
    }
}
