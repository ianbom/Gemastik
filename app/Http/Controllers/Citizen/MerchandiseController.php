<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateReedemsRequest;
use App\Models\Merchandise;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MerchandiseController extends Controller
{

    protected $pointService;
    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function index()
    {
        $userPoints = Auth::user()->points_balance ?? 0;
        $merchandise = Merchandise::where('is_active', true)->get();
        return Inertia::render('Citizen/Merchandise/MerchandisePage', ['merchandise' => $merchandise, 'userPoints' => $userPoints]);
    }

    public function store(CreateReedemsRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $this->pointService->reedemMerchandise($data);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['err' => $th->getMessage()]);
        }
    }
    public function viewMyMerchandise()
    {
        $user = Auth::user();
        $reedems = $user->reedems()->with('merchandise')->latest()->get();
        return Inertia::render('Citizen/Merchandise/MyMerchandisePage', [
            'reedems' => $reedems
        ]);
    }
}
