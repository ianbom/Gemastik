<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\NotificationJob;
use App\Models\City;
use App\Models\District;
use App\Models\Donation;
use App\Models\Mission;
use App\Models\Report;
use App\Services\PointService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $reportService;
    protected $pointSerivce;
    public function __construct(ReportService $reportService, PointService $pointSerivce)
    {
        $this->reportService = $reportService;
        $this->pointSerivce = $pointSerivce;
    }


    public function index(Request $request)
    {

        $filters = [];
        $filters = $this->reportService->buildFilter($request);
        $reports = $this->reportService->getReportByFilter($filters);


        $cities = City::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();

        return view('admin.reports.index', [
            'reports' => $reports,
            'filters' => $filters,
            'cities' => $cities,
            'districts' => $districts
        ]);
    }

    public function edit(Report $report)
    {
        $donations = Donation::where('report_id', $report->id)->where('status', 'paid')->get();
        $mission = Mission::where('report_id', $report->id)->first();
        return view('admin.reports.edit', ['report' => $report, 'donations' => $donations, 'mission' => $mission]);
    }

    public function acceptReport(Report $report, Request $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $this->pointSerivce->increamentPoint('Verifikasi Laporan, selamat anda mendapatkan 100 point',
            Report::class, $report->id, 100,$report->reporter_id);
            NotificationJob::dispatch('Laporan Diverifikasi', 'Laporan kamu telah diverifikasi oleh admin', $report->reporter_id, 'Report');

            $assignedType = $request->input('assigned_type', 'community');
            $report = $this->reportService->updateStatus(
                $report->id,
                'verified',
                $user->id,
                null,
                $assignedType
            );




            DB::commit();
            return redirect()->back()->with('success', 'Aduan berhasil diverifikasi');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['err' => $th->getMessage()]);
        }
    }

    public function rejectReport(Report $report, Request $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {

            NotificationJob::dispatch('Laporan Ditolak Admin', 'Laporan ditolak admin karena tidak adanya bukti',
            $report->reporter_id, 'Report');
            $assignedType = $request->input('assigned_type', 'community');
            $report = $this->reportService->updateStatus(
                $report->id,
                'rejected',
                $user->id,
                null,
                $assignedType
            );

            DB::commit();
            return redirect()->back()->with('success', 'Aduan berhasil ditolak');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['err' => $th->getMessage()]);
        }
    }

    public function underAuthority(Report $report, Request $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
             $this->pointSerivce->increamentPoint('Verifikasi Laporan',
            Report::class, $report->id, 100,$report->reporter_id);
            NotificationJob::dispatch('Laporan Diverifikasi', 'Laporan kamu telah diverifikasi oleh admin', $report->reporter_id, 'Report');
            $assignedType = $request->input('assigned_type', 'community');
            $report = $this->reportService->updateStatus(
                $report->id,
                'under-authority',
                $user->id,
                null,
                $assignedType
            );

            DB::commit();
            return redirect()->back()->with('success', 'Aduan ditangani oleh pihak berwenang');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['err' => $th->getMessage()]);
        }
    }

    public function update(Report $report, Request $request)
    {
        DB::beginTransaction();
        try {

            $report->update($request->all());
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['err' => $th->getMessage()]);
        }
    }

    public function toggleDonation(Request $request, $id)
{
    try {
        $report = Report::findOrFail($id);

        $report->is_donation = !$report->is_donation;
        $report->save();

        $status = $report->is_donation ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Status donasi berhasil {$status}.");

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah status donasi.');
    }
}

}
