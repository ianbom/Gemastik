<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\NotificationJob;
use App\Models\Reedems;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReedemsController extends Controller
{
    protected $pointService;

    public function __construct(PointService $pointService){
        $this->pointService = $pointService;
    }


     public function index(){
        $redeems = Reedems::all();
        return view('admin.redeems.index', ['redeems' => $redeems]);
    }

  public function edit($id) {
    // Load relationships for better performance
    $redeems = Reedems::with('user')->findOrFail($id);
    // return response()->json(['redems' => $redeems]);
    return view('admin.redeems.edit', ['redeems' => $redeems]);
}

public function update($id, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'shipping_address' => 'nullable|string|max:1000',
            'tracking_number' => 'nullable|string|max:255',
            'logistic' => 'nullable'
        ]);

        $redeem = Reedems::with(['user', 'merchandise'])->findOrFail($id);
        $oldStatus = $redeem->status; // Simpan status lama untuk perbandingan

        if ($oldStatus === $validated['status']) {
            $redeem->update($validated);
            return redirect()->back()->with('info', 'Tidak ada perubahan status. Data lain berhasil diperbarui.');
        }

        DB::beginTransaction();
        try {
            // Update status redeem dan data lainnya
            $redeem->update($validated);

            $user = $redeem->user;
            // dd($user);
            $merchandise = $redeem->merchandise;
            $notificationTitle = 'Update Penukaran Hadiah';
            $notificationBody = '';

            switch ($redeem->status) {
                case 'processing':
                    $notificationBody = "Kabar baik! Penukaran Anda untuk '{$merchandise->name}' sedang kami proses.";
                    break;

                case 'shipped':
                    $notificationBody = "Pesanan Anda untuk '{$merchandise->name}' telah dikirim! No. Resi: {$redeem->tracking_number}.";
                    break;

                case 'completed':
                    $notificationBody = "Penukaran '{$merchandise->name}' telah selesai. Terima kasih atas partisipasi Anda!";
                    break;

                case 'cancelled':
                    if (in_array($oldStatus, ['pending', 'processing'])) {

                        // Kembalikan poin ke pengguna
                        $this->pointService->increamentPoint(
                            "Pengembalian poin dari pembatalan penukaran: {$merchandise->name}",
                            Reedems::class,
                            $redeem->id,
                            $redeem->points_spent,
                            $user->id
                        );

                        $notificationBody = "Penukaran Anda untuk '{$merchandise->name}' telah dibatalkan. {$redeem->points_spent} poin telah dikembalikan ke akun Anda.";
                    } else {
                        // Jika dibatalkan dari status 'shipped' atau 'completed', mungkin tidak perlu kembalikan poin.
                        // Untuk saat ini kita anggap pembatalan hanya dari pending/processing
                        $notificationBody = "Penukaran Anda untuk '{$merchandise->name}' telah dibatalkan.";
                    }
                    break;
            }

            if (!empty($notificationBody)) {
                NotificationJob::dispatch($notificationTitle, $notificationBody, $user->id, 'Redeem');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Status penukaran berhasil diperbarui.');

        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status.');
        }
    }


}
