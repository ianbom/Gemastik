<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Donation;
use App\Models\Mission;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
    {
        // --- DATA UNTUK KARTU STATISTIK UTAMA ---
        $pendingReportsCount = Report::where('status', 'pending')->count();
        $activeMissionsCount = Mission::whereIn('status', ['open', 'on-progress'])->count();
        $totalUsersCount = User::count();
        $donationsThisMonth = Donation::where('status', 'paid')
                                      ->whereYear('created_at', now()->year)
                                      ->whereMonth('created_at', now()->month)
                                      ->sum('amount');

        $pendingReports = Report::with(['reporter', 'city'])
                                ->where('status', 'pending')
                                ->latest()
                                ->take(5)
                                ->get();


        $newCommunities = Community::with('user')

                                       ->latest()
                                       ->take(5)
                                       ->get();

        // --- DATA UNTUK VISUALISASI ---
        // 1. Grafik Laporan Masuk (30 hari terakhir)
        $reportsByDay = Report::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                                ->where('created_at', '>=', now()->subDays(30))
                                ->groupBy('date')
                                ->orderBy('date', 'asc')
                                ->get();

        $reportsChartData = [
            'labels' => $reportsByDay->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d M')),
            'data' => $reportsByDay->pluck('count'),
        ];

        // 2. Grafik Komposisi Kategori Laporan
        $categories = Report::select('category', DB::raw('count(*) as count'))
                              ->whereNotNull('category')
                              ->groupBy('category')
                              ->orderByDesc('count')
                              ->get();

        $categoryChartData = [
            'labels' => $categories->pluck('category'),
            'data' => $categories->pluck('count'),
        ];

        // 3. Peta Sebaran Laporan Terbaru
        $mapReports = Report::whereIn('status', ['pending', 'verified'])
                              ->whereNotNull(['latitude', 'longitude'])
                              ->latest()
                              ->take(30) // Ambil 30 laporan terbaru yang punya lokasi
                              ->get(['id', 'title', 'latitude', 'longitude']);

        // --- DATA UNTUK AKTIVITAS TERKINI (Contoh Sederhana) ---
        // Untuk production, ini idealnya diambil dari tabel log aktivitas khusus.
        // Di sini kita gabungkan beberapa data terbaru sebagai contoh.
        $recentUsers = User::latest()->take(3)->get()->map(fn($user) => ['type' => 'user_registered', 'data' => $user, 'date' => $user->created_at]);
        $recentReports = Report::with('reporter')->latest()->take(3)->get()->map(fn($report) => ['type' => 'report_created', 'data' => $report, 'date' => $report->created_at]);
        $recentMissions = Mission::latest()->take(3)->get()->map(fn($mission) => ['type' => 'mission_created', 'data' => $mission, 'date' => $mission->created_at]);

        $activityFeed = $recentUsers->concat($recentReports)->concat($recentMissions)
                                   ->sortByDesc('date')
                                   ->take(8); // Ambil 8 aktivitas terbaru dari gabungan

        // Mengirim semua data ke view Blade
        return view('admin.dashboard.index', compact(
            'pendingReportsCount',
            'activeMissionsCount',
            'totalUsersCount',
            'donationsThisMonth',
            'pendingReports',
            'newCommunities',
            'reportsChartData',
            'categoryChartData',
            'mapReports',
            'activityFeed'
        ));
    }
}
