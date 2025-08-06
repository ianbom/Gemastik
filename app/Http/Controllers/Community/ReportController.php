<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Jobs\NotificationJob;
use App\Services\ReportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use Throwable;
use Inertia\Inertia;

class ReportController extends Controller
{

    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }
    public function create()
    {
        $provinces = Province::with('cities.districts')->get();

        return Inertia::render('Community/Report/CreateReportPage', [
            'provinces' => $provinces
        ]);
    }

    public function store(ReportRequest $request)
    {
        DB::beginTransaction();
        try {
            Log::info('Store method called', [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'validated_data' => $request->validated()
            ]);
            if (!Auth::check()) {
                Log::warning('User not authenticated in store method');
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $report = $this->reportService->createReport($request->validated());
            Log::info('Report created successfully', ['report_id' => $report->id]);

            NotificationJob::dispatch(
                'Laporan Baru Telah Dibuat',
                'Laporan anda telah berhasil dibuat. Silakan cek detail laporan Anda.',
                $report->reporter_id,
                'report'
            );
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil dibuat',
                'data' => $report
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating report', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function viewMyReportsPage()
    {
        $filters = request()->only(['status', 'city_id', 'district_id', 'category', 'search']);
        $filters['reporter_id'] = Auth::id();
        $perPage = request()->get('per_page', 15);
        $reports = $this->reportService->getReports($filters, $perPage);

        return Inertia::render('Community/Report/ReportPage', [
            'reports' => $reports,
            'myReports' => true

        ]);
    }
    public function viewAllReportsPage()
    {
        $filters = request()->only(['status', 'city_id', 'district_id', 'category', 'search']);
        $perPage = request()->get('per_page', 15);
        $reports = $this->reportService->getReports($filters, $perPage);

        return Inertia::render('Community/Report/ReportPage', [
            'reports' => $reports,
            'myReports' => false,
        ]);
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'city_id', 'district_id', 'category', 'reporter_id', 'search']);
            $perPage = $request->get('per_page', 15);
            $reports = $this->reportService->getReports($filters, $perPage);
            return response()->json([
                'status' => 'success',
                'message' => 'Data laporan berhasil diambil',
                'data' => $reports
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $report = $this->reportService->getReportById($id);
            if (!$report) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Laporan tidak ditemukan'
                ], 404);
            }
            return Inertia::render('Community/Report/ReportDetailPage', [
                'report' => $report
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function myReports(Request $request)
    {
        try {
            $filters = $request->only(['status', 'city_id', 'district_id', 'category', 'search']);
            $filters['reporter_id'] = Auth::id();
            $perPage = $request->get('per_page', 15);

            $reports = $this->reportService->getReports($filters, $perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Data laporan Anda berhasil diambil',
                'data' => $reports
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateReportRequest $request, int $id)
    {
        try {
            // Debug log
            Log::info('Update method called', [
                'user_id' => Auth::id(),
                'report_id' => $id,
                'request_data' => $request->all(),
                'validated_data' => $request->validated()
            ]);

            // Cek apakah user sudah login
            if (!Auth::check()) {
                Log::warning('User not authenticated in update method');
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $report = $this->reportService->updateReport($id, $request->validated());

            if (!$report) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Laporan tidak ditemukan atau Anda tidak memiliki akses'
                ], 404);
            }

            Log::info('Report updated successfully', ['report_id' => $report->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil diperbarui',
                'data' => $report
            ], 200);
        } catch (Exception $e) {
            Log::error('Error updating report', [
                'report_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateMedia(Request $request, int $id)
    {
        try {
            // Validasi request untuk media
            $request->validate([
                'media' => 'sometimes|array',
                'media.*' => 'file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv,wmv|max:10240', // 10MB max
                'media_urls' => 'sometimes|array',
                'media_urls.*' => 'url',
                'media_types' => 'sometimes|array',
                'media_types.*' => 'in:image,video',
            ]);

            // Cek apakah user sudah login
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $success = $this->reportService->updateReportMedia($id, $request->all());

            if (!$success) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Laporan tidak ditemukan atau Anda tidak memiliki akses'
                ], 404);
            }

            // Ambil data report yang sudah diupdate
            $report = $this->reportService->getReportById($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Media laporan berhasil diperbarui',
                'data' => $report
            ], 200);
        } catch (Exception $e) {
            Log::error('Error updating report media', [
                'report_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui media laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registerAsVolunteer(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->only(['mission_id']);
            $isLeader = false;
            $missionVolunteer = $this->reportService->registerAsVolunteer($data, $isLeader);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran sebagai relawan berhasil',
                'data' => $missionVolunteer
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendaftar sebagai relawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function registerAsLeaderVolunteer(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->only(['mission_id']);
            $isLeader = true;
            $missionVolunteer = $this->reportService->registerAsVolunteer($data, $isLeader);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran sebagai relawan berhasil',
                'data' => $missionVolunteer
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendaftar sebagai relawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function vote(Request $request, Report $report)
    {
        $type = $request->input('vote_type'); // pastikan ini vote_type
        \Log::info('Vote type masuk:', ['type' => $type]);
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Cari apakah user sudah pernah vote
        $existingVote = $report->votes()->where('user_id', $user->id)->first();

        if ($existingVote) {
            \Log::info('Existing vote vs new:', [
                'existing' => $existingVote->vote_type,
                'new' => $type,
            ]);

            if ($existingVote->vote_type === $type) {
                $existingVote->delete(); // toggle: batal vote
            } else {
                $existingVote->update(['vote_type' => $type]); // ganti vote
                \Log::info('Updated vote now is:', ['after_update' => $existingVote->vote_type]);
            }
        } else {
            $report->votes()->create([
                'user_id' => $user->id,
                'vote_type' => $type,
            ]);
        }


        $upvotes = $report->votes()->where('vote_type', 'upvote')->count();
        $dislikes = $report->votes()->where('vote_type', 'dislike')->count();


        $report->update([
            'upvotes_count' => $upvotes,
            'dislikes_count' => $dislikes,
        ]);
        $latestVote = $report->votes()->where('user_id', $user->id)->first();
        \Log::info('After update or insert, vote is now:', ['vote_type' => $latestVote?->vote_type]);

        $yourVote = $report->votes()->where('user_id', $user->id)->first()?->vote_type;

        return response()->json([
            'upvotes_count' => $upvotes,
            'dislikes_count' => $dislikes,
            'your_vote' => $yourVote,
        ]);
    }
}
