<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBadgeRequest;
use App\Http\Requests\UpdateBadgeRequest;
use App\Models\Badge;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BadgeController extends Controller
{

    protected $badgeService;

    public function __construct(BadgeService $badgeService){

        $this->badgeService = $badgeService;
    }

    public function index(){
        $badges = Badge::orderBy('title', 'asc')->get();
        return view('admin.badges.index', ['badges' => $badges]);
    }

    public function create(){
        return view('admin.badges.create');
    }

    public function store(CreateBadgeRequest $request){

        DB::beginTransaction();
        try {
            $badge = $this->badgeService->createBadge($request->validated());
            DB::commit();
            return redirect()->route('admin.badges.index')->with('success', 'Badge berhasil dibuat');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function edit(Badge $badge){

        return view('admin.badges.edit', [ 'badge' => $badge]);
    }

    public function update(UpdateBadgeRequest $request, Badge $badge){

         DB::beginTransaction();
        try {
            $badge = $this->badgeService->updateBadge($request->validated(), $badge);
            DB::commit();
            return redirect()->route('admin.badges.index')->with('success', 'Badge berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['err' => $th->getMessage() ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }

    }
}
