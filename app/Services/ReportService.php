<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\Mission;
use App\Models\MissionVolunteer;
use App\Models\Report;
use App\Models\ReportMedia;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportService extends Service
{

    public function __construct()
    {
        //
    }

    public function createReport(array $data): Report
    {
        try {
            DB::beginTransaction();

            $report = Report::create([
                'reporter_id' => Auth::id(),
                'province_id' => $data['province_id'],
                'city_id' => $data['city_id'],
                'district_id' => $data['district_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'category' => $data['category'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'address' => $data['address'] ?? null,
                'status' => 'pending',
                'upvotes_count' => 0,
                'dislikes_count' => 0,
            ]);


            if (isset($data['media']) && is_array($data['media'])) {
                foreach ($data['media'] as $file) {
                    $mediaUrl = $this->uploadFile($file);
                    $mediaType = $this->getMediaType($file);

                    ReportMedia::create([
                        'report_id' => $report->id,
                        'media_url' => $mediaUrl,
                        'media_type' => $mediaType,
                    ]);
                }
            }

            // Handle media URLs (jika ada external URLs)
            if (isset($data['media_urls']) && isset($data['media_types'])) {
                $mediaUrls = $data['media_urls'];
                $mediaTypes = $data['media_types'];

                for ($i = 0; $i < count($mediaUrls); $i++) {
                    if (isset($mediaUrls[$i]) && isset($mediaTypes[$i])) {
                        ReportMedia::create([
                            'report_id' => $report->id,
                            'media_url' => $mediaUrls[$i],
                            'media_type' => $mediaTypes[$i],
                        ]);
                    }
                }
            }

            DB::commit();
            // Load relationships untuk response
            $report->load(['reporter', 'city', 'district', 'media']);

            return $report;
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Gagal membuat laporan: ' . $e->getMessage());
        }
    }


    private function uploadFile($file): string
    {
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('reports', $fileName, 'public');

        return $filePath;
    }

    private function getMediaType($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'wmv'];

        if (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $videoExtensions)) {
            return 'video';
        }

        return 'image';
    }

    public function getReportById(int $id): ?Report
    {
        return Report::with([
            'reporter',
            'province',
            'city',
            'district',
            'verifiedByUser',
            'completedByUser',
            'media',
            'mission.creator',
            'mission.volunteers',
            'mission.documentation',
            'mission.documentation.uploader',
            'mission.chatGroups'

        ])
            ->find($id);
    }

    public function getReports(array $filters = [], int $perPage = 15)
    {
        $query = Report::with(['reporter', 'city', 'district', 'media', 'province', 'mission']);

        // Filter by status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by city
        if (isset($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        // Filter by district
        if (isset($filters['district_id'])) {
            $query->where('district_id', $filters['district_id']);
        }

        // Filter by category
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Filter by reporter (untuk melihat laporan sendiri)
        if (isset($filters['reporter_id'])) {
            $query->where('reporter_id', $filters['reporter_id']);
        }

        // Search by title or description
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Order by created_at desc by default
        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }



    public function updateStatus(int $id, string $status, ?int $userId = null, ?string $completionDetails = null, ?string $assignedType = null): bool
    {
        try {
            DB::beginTransaction();

            $report = Report::findOrFail($id);
            $updateData = ['status' => $status];

            if ($status === 'verified') {
                $updateData['verified_by_user_id'] = $userId;
                $updateData['verified_at'] = now();
            } elseif ($status === 'completed') {
                $updateData['completed_by_user_id'] = $userId;
                $updateData['completion_details'] = $completionDetails;
            }

            $report->update($updateData);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Gagal mengupdate status laporan: ' . $e->getMessage());
        }
    }

    private function approveAndMakeReportMission($reportId, $assignedType)
    {

        Mission::create([
            'report_id' => $reportId,
            'creator_user_id' => Auth::id(),
            'assigned_to_type' => $assignedType,
            'status' => 'open',
        ]);
    }

    public function updateReport(int $id, array $data): ?Report
    {
        try {
            DB::beginTransaction();

            // Cari report berdasarkan ID dan pastikan hanya pemilik yang bisa update
            $report = Report::where('id', $id)
                ->where('reporter_id', Auth::id())
                ->first();

            if (!$report) {
                DB::rollback();
                return null;
            }

            // Cek apakah status masih pending (hanya laporan pending yang bisa diupdate)
            if ($report->status !== 'pending') {
                DB::rollback();
                throw new Exception('Laporan yang sudah diverifikasi atau sedang diproses tidak dapat diubah');
            }

            // Update data report
            $report->update([
                'city_id' => $data['city_id'] ?? $report->city_id,
                'district_id' => $data['district_id'] ?? $report->district_id,
                'title' => $data['title'] ?? $report->title,
                'description' => $data['description'] ?? $report->description,
                'category' => $data['category'] ?? $report->category,
                'latitude' => $data['latitude'] ?? $report->latitude,
                'longitude' => $data['longitude'] ?? $report->longitude,
                'address' => $data['address'] ?? $report->address,
            ]);

            // Handle media update jika ada
            if (isset($data['media']) && is_array($data['media'])) {
                // Hapus media lama
                ReportMedia::where('report_id', $report->id)->delete();

                // Upload media baru
                foreach ($data['media'] as $file) {
                    $mediaUrl = $this->uploadFile($file);
                    $mediaType = $this->getMediaType($file);

                    ReportMedia::create([
                        'report_id' => $report->id,
                        'media_url' => $mediaUrl,
                        'media_type' => $mediaType,
                    ]);
                }
            }

            // Handle media URLs update (jika ada external URLs)
            if (isset($data['media_urls']) && isset($data['media_types'])) {
                // Hapus media lama
                ReportMedia::where('report_id', $report->id)->delete();

                $mediaUrls = $data['media_urls'];
                $mediaTypes = $data['media_types'];

                for ($i = 0; $i < count($mediaUrls); $i++) {
                    if (isset($mediaUrls[$i]) && isset($mediaTypes[$i])) {
                        ReportMedia::create([
                            'report_id' => $report->id,
                            'media_url' => $mediaUrls[$i],
                            'media_type' => $mediaTypes[$i],
                        ]);
                    }
                }
            }

            DB::commit();

            // Load relationships untuk response
            $report->load(['reporter', 'city', 'district', 'media']);

            return $report;
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Gagal memperbarui laporan: ' . $e->getMessage());
        }
    }

    // Method tambahan untuk update media saja (opsional)
    public function updateReportMedia(int $id, array $mediaData): bool
    {
        try {
            DB::beginTransaction();

            $report = Report::where('id', $id)
                ->where('reporter_id', Auth::id())
                ->where('status', 'pending')
                ->first();

            if (!$report) {
                DB::rollback();
                return false;
            }

            // Hapus media lama
            $oldMedia = ReportMedia::where('report_id', $report->id)->get();
            foreach ($oldMedia as $media) {
                // Hapus file dari storage
                if (file_exists(storage_path('app/public/' . $media->media_url))) {
                    unlink(storage_path('app/public/' . $media->media_url));
                }
            }
            ReportMedia::where('report_id', $report->id)->delete();

            // Upload media baru
            if (isset($mediaData['media']) && is_array($mediaData['media'])) {
                foreach ($mediaData['media'] as $file) {
                    $mediaUrl = $this->uploadFile($file);
                    $mediaType = $this->getMediaType($file);

                    ReportMedia::create([
                        'report_id' => $report->id,
                        'media_url' => $mediaUrl,
                        'media_type' => $mediaType,
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Gagal memperbarui media laporan: ' . $e->getMessage());
        }
    }

    public function registerAsVolunteer($data, bool $isLeader)
    {
        $user = Auth::user();

        $missionVolunter = MissionVolunteer::create([
            'mission_id' => $data['mission_id'],
            'user_id' => $user->id,
            'is_leader' => $isLeader,
            'status' => 'pending',
        ]);
        return $missionVolunter;
    }


    public function getReportByFilter($filters)
    {
        $allowedFilters = [
            'reports.status'      => 'value',
            'reports.city_id'     => 'value',
            'reports.district_id' => 'value',
            'reports.category'    => 'value',
            'reports.created_at'  => 'date',
            'reports.verified_at' => 'date',
            'reports.title'       => 'like',
            'reports.address'     => 'like',
        ];

        $selectColumns = [
            'reports.*',
            'cities.name as city_name',
            'districts.name as district_name',
            'reporter.name as reporter_name',
        ];

        $query = Report::select($selectColumns)
            // PERBAIKAN: Sesuaikan join untuk tabel 'reports'
            ->join('cities', 'reports.city_id', '=', 'cities.id')
            ->join('districts', 'reports.district_id', '=', 'districts.id')
            ->join('users as reporter', 'reports.reporter_id', '=', 'reporter.id') // Join untuk mendapatkan nama pelapor
            ->orderBy('reports.id', 'desc');


        $query = $this->applyFilters($query, $filters, $allowedFilters);

        $query->with([
            'city',
            'district',
            'reporter'
        ]);

        return $query->get();
    }

    public function buildFilter($request)
    {
        $filters = [];

        if ($request->filled('status')) {
            $filters['reports.status'] = $request->input('status');
        }
        if ($request->filled('city_id')) {
            $filters['reports.city_id'] = $request->input('city_id');
        }
        if ($request->filled('district_id')) {
            $filters['reports.district_id'] = $request->input('district_id');
        }

        if ($request->filled('category')) {
            $filters['reports.category'] = $request->input('category');
        }

        if ($request->filled('search')) {

            $filters['reports.title'] = $request->input('search');
        }

        if ($request->filled('created_from') && $request->filled('created_to')) {
            $filters['reports.created_at'] = [
                'start' => $request->input('created_from'),
                'end'   => $request->input('created_to')
            ];
        }


        if ($request->filled('verified_from') && $request->filled('verified_to')) {
            $filters['reports.verified_at'] = [
                'start' => $request->input('verified_from'),
                'end'   => $request->input('verified_to')
            ];
        }

        return $filters;
    }

    public function getReportDonation($reportId){
        $donations = Donation::with('user', 'report')->where('report_id', $reportId)->where('status', 'paid')->get();
        return $donations;
    }

    public function getReportTotalDonation($reportId){

        $donations = $this->getReportDonation($reportId);

        $totalDonations = $donations->where('status', 'paid')->sum('amount');
        return $totalDonations;
    }

    public function getReportTotalDonors($reportId){
        $donations = $this->getReportDonation($reportId);

        $totalDonors = $donations
        ->where('status', 'paid')
        ->distinct('user_id')
        ->count();

        return $totalDonors;
    }
}
