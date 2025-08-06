<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMissionRequest;
use App\Http\Requests\UpdateMissionRequest;
use App\Jobs\NotificationJob;
use App\Models\City;
use App\Models\District;
use App\Models\Mission;
use App\Models\MissionCommunity;
use App\Models\MissionDocumentation;
use App\Models\MissionVolunteer;
use App\Models\Province;
use App\Models\Report;
use App\Services\MissionServices;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MissionController extends Controller
{
    protected $missionService;
    protected $pointService;

    public function __construct(MissionServices $missionService, PointService $pointService)
    {
        $this->missionService = $missionService;
        $this->pointService = $pointService;
    }

    public function store(CreateMissionRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $mission = $this->missionService->createMission($data);

            // Load relationships for response
            $mission->load([
                'creator:id,name,email',
                'city:id,name',
                'district:id,name',
                'assignedVolunteer:id,name,email',
                'report:id,title'
            ]);
            DB::commit();

            return redirect()->route('admin.missions.index')->with('success', 'Misi berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat misi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $filters = $this->missionService->buildFilter($request);

            $missions = $this->missionService->getMissionByFilters($filters);

            $cities = City::all();
            $districts = District::all();

            return view('admin.missions.index', ['missions' => $missions, 'cities' => $cities, 'districts' => $districts]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil misi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $usedReportIds = Mission::pluck('report_id');

        $reports = Report::whereIn('status', ['verified', 'under-authority'])
            ->whereNotIn('id', $usedReportIds)
            ->orderBy('title', 'asc')
            ->get();

        return view('admin.missions.create', ['reports' => $reports]);
    }

    public function update(UpdateMissionRequest $request, Mission $mission)
    {
        DB::beginTransaction();
        try {
            $this->missionService->updateMission($mission, $request->validated());

            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Misi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui misi: ' . $e->getMessage());
        }
    }

    public function edit(Mission $mission)
    {
        $volunteers = $this->missionService->getMissionVolunteers($mission->id);
        $missionDocumentations = $this->missionService->getMissionDocumentations($mission->id);
        $provinces = Province::all();
        $cities = City::all();
        $districts = District::all();

        $reports = $this->missionService->getVerifiedAndUniqueReport($mission->id);

        return view('admin.missions.edit', [
            'mission' => $mission,
            'volunteers' => $volunteers,
            'missionDocumentations' => $missionDocumentations,
            'cities' => $cities,
            'districts' => $districts,
            'reports' => $reports,
            'provinces' => $provinces
        ]);
    }

    public function updateStatusVolunteer(Request $request, MissionVolunteer $missionVolunteer)
    {

        DB::beginTransaction();
        try {
            $missionVolunteer->update([
                'participation_status' => $request->participation_status,
                'is_leader' => $request->is_leader,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Status volunter berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['err' => $th->getMessage()]);
        }
    }

    public function shareMissionPoint(Mission $mission){

        $missionVolunteers = MissionVolunteer::where('mission_id', $mission->id)
        ->where('participation_status', 'attended')->get();

        if ($mission->is_point_shared) {
        return redirect()->back()->with('info', 'Poin untuk misi ini sudah pernah dibagikan sebelumnya.');
    }

        DB::beginTransaction();
        try {
        $mission->update(['is_point_shared' => true]);

        foreach ($missionVolunteers as $volunteer) {
           $this->pointService->increamentPoint('Misi diselesaikan, selamat anda mendapatkan 60 point',
            Mission::class, $mission->id, 60,$volunteer->user_id);
            NotificationJob::dispatch('Misi Diselesaikan',
            'Terimakasih telah menjadi bagian SobatBumi', $volunteer->user_id, 'Mission');
        }

        DB::commit();
          return redirect()->back()->with('success', 'Point berhasil dikirimkan');
        } catch (\Throwable $th) {
           DB::rollBack();
           return redirect()->back()->with('error', 'Terjadi kesalah ');
        }
    }







    // public function myMissions(Request $request)
    // {
    //     try {
    //         $perPage = $request->query('per_page', 10);
    //         $missions = $this->missionService->getMyMissions($perPage);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Misi saya berhasil diambil',
    //             'data' => [
    //                 'missions' => $missions->items(),
    //                 'pagination' => [
    //                     'current_page' => $missions->currentPage(),
    //                     'per_page' => $missions->perPage(),
    //                     'total' => $missions->total(),
    //                     'last_page' => $missions->lastPage(),
    //                     'from' => $missions->firstItem(),
    //                     'to' => $missions->lastItem(),
    //                 ]
    //             ]
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal mengambil misi: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }



}
