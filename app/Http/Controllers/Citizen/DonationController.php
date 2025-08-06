<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Service\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DonationController extends Controller
{

    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function donateReport(Request $request, $reportId)
    {
        $data = $request->all();
        $user = Auth::user();

        DB::beginTransaction();
        try {
            // PERBAIKAN: Dapatkan hasil dari midtransService yang sudah termasuk donation_id
            $result = $this->midtransService->payTransaction($user, $reportId, $data);

            DB::commit();

            return response()->json([
                'snap_token' => $result['snap_token'],
                'donation_id' => $result['donation_id'],
                'transaction_id' => $result['transaction_id'],
                'message' => 'Success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function paymentSuccess(Request $request, $donationId)
    {
        try {
            $donation = Donation::findOrFail($donationId);

            // Update status donation
            $donation->update([
                'status' => 'paid',
                // 'payment_data' => json_encode($request->all()),
                // 'paid_at' => now()
            ]);

            // Log untuk debugging
            Log::info('Payment success confirmation received', [
                'donation_id' => $donationId,
                'payment_data' => $request->all()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmation received',
                'donation_id' => $donationId
            ]);
        } catch (\Exception $e) {
            Log::error('Payment success confirmation failed', [
                'donation_id' => $donationId,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm payment: ' . $e->getMessage()
            ], 500);
        }
    }
    public function viewMyDonations()
    {
        $user = Auth::user();

        $donations = Donation::with('report')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return Inertia::render('Citizen/Donation/MyDonationPage', [
            'donations' => $donations
        ]);
    }


    public function callBackAfterPayment(Request $request)
    {
        Log::info('Webhook donasi diterima.');

        // Ambil raw JSON body
        $rawBody = $request->getContent();
        $requestData = json_decode($rawBody, true);

        Log::info('Data request webhook:', $requestData);

        try {
            $serverKey = config('midtrans.server_key');

            if (empty($serverKey)) {
                Log::error('Server key Midtrans tidak ditemukan di config.');
                return response()->json(['message' => 'Server configuration error'], 500);
            }

            $orderId = $requestData['order_id'] ?? null;
            $statusCode = $requestData['status_code'] ?? null;
            $grossAmount = $requestData['gross_amount'] ?? null;
            $signatureKeyFromMidtrans = $requestData['signature_key'] ?? null;
            $transactionStatus = $requestData['transaction_status'] ?? null;


            if (empty($orderId) || empty($statusCode) || empty($grossAmount) || empty($signatureKeyFromMidtrans)) {
                Log::warning('Webhook menerima data tidak lengkap untuk validasi signature.');
                return response()->json(['message' => 'Incomplete webhook data'], 400);
            }


            $signatureKey = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);


            if ($signatureKey !== $signatureKeyFromMidtrans) {
                Log::error("Signature key tidak valid untuk Order ID: {$orderId}. Expected: {$signatureKey}, Received: {$signatureKeyFromMidtrans}");
                return response()->json(['message' => 'Invalid signature key'], 403);
            }


            $donation = Donation::where('transaction_id', $orderId)->first();

            if (!$donation) {
                Log::error("Donasi tidak ditemukan untuk transaction_id (Order ID): {$orderId}");
                return response()->json(['message' => 'Donation not found'], 404);
            }


            if (in_array($donation->status, ['paid', 'cancelled', 'expired'])) {
                Log::info("Webhook untuk Order ID: {$orderId} sudah diproses sebelumnya. Status saat ini: {$donation->status}. Diabaikan.");
                return response()->json(['message' => 'Webhook already processed']);
            }


            $donation->payment_response = $requestData;


            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {

                $donation->status = 'paid';
                $donation->payment_method = $requestData['payment_type'] ?? $donation->payment_method;

            } elseif ($transactionStatus == 'pending') {

                $donation->status = 'pending';

            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'failure') {

                $donation->status = 'cancelled';

            } elseif ($transactionStatus == 'expire') {

                $donation->status = 'expired';

            } elseif ($transactionStatus == 'cancel') {

                $donation->status = 'cancelled';
            }

            // 7. Simpan perubahan ke database
            $donation->save();

            Log::info("Status donasi berhasil diperbarui untuk Order ID: {$orderId}. Status baru: {$donation->status}");

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Throwable $th) {
            Log::error('Error di dalam callback webhook donasi: ' . $th->getMessage());
            Log::error('Stack trace: ' . $th->getTraceAsString());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
