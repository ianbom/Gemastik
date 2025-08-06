<?php

namespace App\Service;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;

class MidtransService
{
    /**
     * Create a new class instance.
     */
     public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

 public function payTransaction($user, $reportId, $data)
{
    // Validasi input
    if (!isset($data['amount']) || $data['amount'] < 10000) {
        throw new \Exception('Minimal donasi adalah Rp 10.000');
    }

    $donation = Donation::create([
        'user_id' => $user->id,
        'report_id' => $reportId,
        'amount' => $data['amount'],
        'transaction_id' => 'DONATION-' . time() . '-' . rand(1000, 9999),
        'status' => 'pending'
    ]);

    $params = [
        'transaction_details' => [
            'order_id' => $donation->transaction_id,
            'gross_amount' => (int) $donation->amount, // Pastikan integer
        ],
        'customer_details' => [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? ''
        ],
        // PERBAIKAN: Pindahkan callbacks ke level yang benar
        'callbacks' => [
            'finish' => route('report.show', $reportId)
        ]
    ];

    try {
        $snapToken = Snap::getSnapToken($params);

        // Update donation dengan snap_token
        $donation->update(['snap_token' => $snapToken]);

        // PERBAIKAN: Return array dengan semua data yang diperlukan
        return [
            'snap_token' => $snapToken,
            'donation_id' => $donation->id,
            'transaction_id' => $donation->transaction_id
        ];

    } catch (\Exception $e) {
        // Log error untuk debugging
        Log::error('Midtrans Snap Token Error', [
            'user_id' => $user->id,
            'report_id' => $reportId,
            'amount' => $data['amount'],
            'error' => $e->getMessage(),
            'params' => $params
        ]);

        throw new \Exception('Gagal membuat token pembayaran: ' . $e->getMessage());
    }
}




}
