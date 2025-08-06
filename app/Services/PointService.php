<?php

namespace App\Services;

use App\Models\Merchandise;
use App\Models\Point;
use App\Models\Reedems;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PointService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function increamentPoint($description, $pointType, $pointId, $amount, $userId){
        $user = User::findOrFail($userId);

        Point::create([
            'user_id' => $userId,
            'type' => 'increment',
            'description' => $description,
            'pointable_type' => $pointType,
            'pointable_id' => $pointId,
            'amount' => $amount,
        ]);

        $user->increment('points_balance', $amount);
        return $user->fresh();
    }

     public function decreamentPoint($description, $pointType, $pointId, $amount, $userId){
        $user = User::findOrFail($userId);

        Point::create([
            'user_id' => $user->id,
            'type' => 'decrement',
            'description' => $description,
            'pointable_type' => $pointType,
            'pointable_id' => $pointId,
            'amount' => $amount,
        ]);

        $user->decrement('points_balance', $amount);
        return $user->fresh();
    }

    public function reedemMerchandise($data){
        $user = Auth::user();
        Reedems::create([
            'user_id' => $user->id,
            'merchandise_id' => $data['merchandise_id'],
            'points_spent' => $data['points_spent'],
            'status' => 'pending',
            'shipping_address' => $data['shipping_address'],
            'logistic' => null,
            'tracking_number' => null
        ]);

        $this->decreamentPoint('Penukaran merchandise', Merchandise::class, $data['merchandise_id'], $data['points_spent'], $user->id);
    }
}
