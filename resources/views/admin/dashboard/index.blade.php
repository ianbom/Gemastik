@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen ">
        <div class="container px-4 py-8 mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin SobatBumi</h1>
                <p class="mt-2 text-gray-600">Kelola dan pantau aktivitas platform lingkungan</p>
            </div>
            <!-- Bagian 1: Kartu Statistik Utama -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-2">
                <!-- Laporan Perlu Verifikasi -->
                <div class="p-6 transition-shadow bg-white border-l-4 border-red-500 rounded-lg shadow-md cursor-pointer hover:shadow-lg"
                    onclick="window.location.href='/admin/reports?status=pending'">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-md">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Laporan Menunggu</h3>
                            <p class="text-3xl font-bold text-red-600">{{ $pendingReportsCount }}</p>
                            <p class="text-sm text-gray-500">Perlu verifikasi segera</p>
                        </div>
                    </div>
                </div>

                <!-- Misi Aktif -->
                <div class="p-6 transition-shadow bg-white border-l-4 border-blue-500 rounded-lg shadow-md cursor-pointer hover:shadow-lg"
                    onclick="window.location.href='/admin/missions'">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-md">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Misi Sedang Berlangsung</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $activeMissionsCount }}</p>
                            <p class="text-sm text-gray-500">Aksi nyata berjalan</p>
                        </div>
                    </div>
                </div>

                <!-- Total Pengguna -->
                <div
                    class="p-6 transition-shadow bg-white border-l-4 border-green-500 rounded-lg shadow-md hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-md">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Total Pengguna</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $totalUsersCount }}</p>
                            <p class="text-sm text-gray-500">Komunitas SobatBumi</p>
                        </div>
                    </div>
                </div>

                <!-- Total Donasi Bulan Ini -->
                <div
                    class="p-6 transition-shadow bg-white border-l-4 border-yellow-500 rounded-lg shadow-md hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-md">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Donasi Bulan Ini</h3>
                            <p class="text-3xl font-bold text-yellow-600">{{ $donationsThisMonth }}</p>
                            <p class="text-sm text-gray-500">Dukungan komunitas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Panel Aksi Cepat -->
            <div class="grid grid-cols-1 gap-8 mb-8 lg:grid-cols-2">
                <!-- Laporan Terbaru Menunggu Verifikasi -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Laporan Perlu Verifikasi</h2>
                        <p class="text-sm text-gray-600">Laporan terbaru menunggu persetujuan</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($pendingReports as $report)
                                <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ Str::limit($report->title, 40) }}</h4>
                                        <p class="text-sm text-gray-600">{{ $report->reporter->name }} •
                                            {{ $report->city->name }} • {{ $report->created_at->diffForHumans() }}</p>
                                    </div>
                                    <a href="{{ route('admin.reports.show', $report->id) }}"
                                        class="px-3 py-1 text-sm text-white transition-colors bg-yellow-600 rounded-md hover:bg-yellow-700">
                                        Pending
                                    </a>
                                </div>
                            @empty
                                <p class="py-4 text-center text-gray-500">Tidak ada laporan yang perlu diverifikasi saat
                                    ini.
                                </p>
                            @endforelse
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}"
                                class="font-medium text-red-600 hover:text-red-700">
                                Lihat Semua Laporan →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pendaftaran Komunitas Baru -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Komunitas Baru</h2>
                        <p class="text-sm text-gray-600">Pendaftaran komunitas baru</p>
                    </div>



                    <div class="p-6">
                        @foreach ($newCommunities as $community)
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $community->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $community->user->city->name ?? 'Belum melengkapi profile' }} •
                                            {{ $community->member_count }} Anggota </p>
                                    </div>
                                    <button
                                        class="px-3 py-1 text-sm text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700">
                                        Lihat
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.users.index') }}"
                                class="font-medium text-blue-600 hover:text-blue-700">
                                Lihat Semua Pendaftaran →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Visualisasi Data -->
            <div class="grid grid-cols-1 gap-8 mb-8 lg:grid-cols-2">
                <!-- Grafik Laporan 30 Hari Terakhir -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Laporan Masuk (30 Hari Terakhir)</h2>
                        <p class="text-sm text-gray-600">Tren pelaporan harian</p>
                    </div>
                    <div class="p-6">
                        <canvas id="reportChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Komposisi Kategori Laporan -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Kategori Laporan</h2>
                        <p class="text-sm text-gray-600">Distribusi jenis masalah lingkungan</p>
                    </div>
                    <div class="p-6">
                        <canvas id="categoryChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Peta Sebaran Laporan -->
            <div class="mb-8 bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Peta Sebaran Laporan Terbaru</h2>
                    <p class="text-sm text-gray-600">Lokasi 30 laporan terakhir yang masuk</p>
                </div>
                <div class="p-6">
                    <div id="map" class="flex items-center justify-center w-full bg-gray-100 rounded-lg h-96">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7">
                                </path>
                            </svg>
                            <p class="text-gray-600">Peta Interaktif Akan Dimuat Di Sini</p>
                            <p class="mt-2 text-sm text-gray-500">Integrasi dengan Leaflet.js atau Google Maps API</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha26-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grafik Laporan 30 Hari Terakhir (Line Chart)
            const reportCtx = document.getElementById('reportChart')?.getContext('2d');
            if (reportCtx) {
                // Ambil data dinamis dari controller
                const reportData = @json($reportsChartData);
                new Chart(reportCtx, {
                    type: 'line',
                    data: {
                        labels: reportData.labels,
                        datasets: [{
                            label: 'Laporan Masuk',
                            data: reportData.data,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }


            // Grafik Kategori Laporan (Pie Chart)
            const categoryChartCtx = document.getElementById('categoryChart');
            if (categoryChartCtx) {
                // 1. Ambil data dinamis dari controller
                const categoryData = @json($categoryChartData ?? ['labels' => [], 'data' => []]);

                new Chart(categoryChartCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        // 2. Gunakan labels dinamis dari controller
                        labels: categoryData.labels,
                        datasets: [{
                            // 3. Gunakan data (jumlah) dinamis dari controller
                            data: categoryData.data,
                            backgroundColor: [
                                '#10b981', // emerald-500
                                '#3b82f6', // blue-500
                                '#ef4444', // red-500
                                '#f97316', // orange-500
                                '#8b5cf6', // violet-500
                                '#64748b', // slate-500
                                '#eab308', // yellow-500
                                '#222121FF',
                                '#C514CEFF',
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });
            }



            const mapContainer = document.getElementById('map');
            if (mapContainer) {
                const mapData = @json($mapReports);
                const map = L.map('map').setView([-2.5489, 118.0149], 5); // Center of Indonesia
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                if (mapData.length > 0) {
                    mapData.forEach(report => {
                        if (report.latitude && report.longitude) {
                            L.marker([report.latitude, report.longitude]).addTo(map)
                                .bindPopup(
                                    `<b>${report.title}</b><br><a href="/admin/reports/${report.id}" target="_blank">Lihat detail</a>`
                                );
                        }
                    });
                } else {
                    mapContainer.innerHTML =
                        '<div class="p-4 text-center"><p class="text-gray-500">Tidak ada data lokasi laporan untuk ditampilkan.</p></div>';
                }
            }

        });
    </script>
@endpush
