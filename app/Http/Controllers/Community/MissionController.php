<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MissionServices;
use App\Models\City;
use App\Models\District;
use Inertia\Inertia;
use App\Models\Province;
use App\Models\Mission;
use App\Models\MissionVolunteer;
use Illuminate\Support\Facades\Auth;
use App\Models\MissionDocumentation;

class MissionController extends Controller
{
    protected $missionService;

    public function __construct(MissionServices $missionService)
    {
        $this->missionService = $missionService;
    }
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['content_type', 'search']);
            $perPage = $request->get('per_page', 15);
            $missions = $this->missionService->getMissions($filters, $perPage);
            return Inertia::render('Citizen/Mission/MissionPage', [
                'missions' => $missions,
                'myMissions' => false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil misi: ' . $e->getMessage()
            ], 500);
        }
    }
    public function myMissions(Request $request)
    {
        try {
            $filters = $request->only([
                'status',
                'city_id',
                'district_id',
                'assigned_to_type',
                'creator_user_id',
                'assigned_volunteer_id',
                'search',
                'date_from',
                'date_to',
                'participation_status',
            ]);

            $perPage = $request->get('per_page', 15);

            $missions = $this->missionService->getMissionsJoined(Auth::user(), $filters, $perPage);
            return Inertia::render('Citizen/Mission/MissionPage', [
                'missions' => $missions,
                'myMissions' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil misi yang diikuti: ' . $e->getMessage()
            ], 500);
        }
    }



    public function join(Request $request, $missionId)
    {
        $request->validate([
            'is_leader' => 'required|boolean',
        ]);

        $mission = Mission::findOrFail($missionId);
        $already = MissionVolunteer::where('mission_id', $missionId)
            ->where('user_id', Auth::id())
            ->exists();

        if ($already) {
            return back()->with('error', 'Anda sudah mendaftar untuk misi ini.');
        }

        MissionVolunteer::create([
            'mission_id' => $missionId,
            'user_id' => Auth::id(),
            'is_leader' => $request->is_leader,
            'participation_status' => 'pending',
        ]);
        return back()->with('success', 'Anda berhasil mendaftar untuk misi ini. Tunggu persetujuan dari Admin.');
    }
    public function attendance(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
            'mission_id' => 'required|integer|exists:missions,id',
        ]);
        MissionVolunteer::where('mission_id', $request->mission_id)
            ->whereIn('user_id', $request->user_ids)
            ->update(['participation_status' => 'attended']);
        MissionVolunteer::where('mission_id', $request->mission_id)
            ->where('is_leader', true)
            ->update(['participation_status' => 'attended']);

        return back()->with('success', 'Presensi berhasil disimpan.');
    }
    public function attend(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
            'mission_id' => 'required|integer|exists:missions,id',
        ]);
        MissionVolunteer::where('mission_id', $request->mission_id)
            ->where('is_leader', false)
            ->update(['participation_status' => 'confirmed']);
        MissionVolunteer::where('mission_id', $request->mission_id)
            ->whereIn('user_id', $request->user_ids)
            ->where('is_leader', false)
            ->update(['participation_status' => 'attended']);

        MissionVolunteer::where('mission_id', $request->mission_id)
            ->where('is_leader', true)
            ->update(['participation_status' => 'attended']);
        return back()->with('success', 'Presensi berhasil disimpan.');
    }
    public function cancel(Mission $mission)
    {
        $user = Auth::user();

        $isPending = $mission->volunteers()
            ->where('user_id', $user->id)
            ->whereIn('participation_status', ['pending', 'confirmed'])
            ->exists();

        if (! $isPending) {
            return back()->withErrors(['message' => 'Tidak bisa membatalkan pendaftaran yang sudah diproses.']);
        }
        $mission->volunteers()->updateExistingPivot($user->id, [
            'participation_status' => 'cancelled',
        ]);

        return back()->with('success', 'Pendaftaran misi berhasil dibatalkan.');
    }
    public function uploadDocumentation(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'media.*' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,wmv|max:10240',
            'mission_id' => 'required|exists:missions,id',
            'content' => 'nullable|string|max:1000',
        ]);
        foreach ($request->file('media') as $file) {
            $path = $file->store('mission-documentations/media', 'public');
            $mimeType = $file->getMimeType();
            $mediaType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';
            MissionDocumentation::create([
                'mission_id' => $request->mission_id,
                'uploader_user_id' => $user->id,
                'media_url' => $path,
                'media_type' => $mediaType,
                'content' => $request->content,
            ]);
        }
        return back()->with('success', 'Dokumentasi misi berhasil diunggah.');
    }
}
