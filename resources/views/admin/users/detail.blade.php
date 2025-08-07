@extends('admin.layouts.app')

@section('content')
    @include('admin.components.notification')

    <div class="container px-4 py-6 mx-auto">
        <!-- Header Section -->
        <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Detail User</h1>
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 text-white transition duration-200 bg-gray-500 rounded-md hover:bg-gray-600">
                    <i class="mr-2 fas fa-arrow-left"></i>Kembali
                </a>
            </div>

            <!-- User Profile Section -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        @if ($user->profile_url)
                            <img src="{{ asset('storage/' . $user->profile_url) }}" alt="Profile"
                                class="object-cover w-20 h-20 rounded-full">
                        @else
                            <div class="flex items-center justify-center w-20 h-20 bg-gray-300 rounded-full">
                                <i class="text-2xl text-gray-600 fas fa-user"></i>
                            </div>
                        @endif
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <span
                                class="inline-block px-3 py-1 text-sm font-medium rounded-full
                            @if ($user->role === 'admin') bg-red-100 text-red-800
                            @elseif($user->role === 'superadmin') bg-purple-100 text-purple-800
                            @elseif($user->role === 'community') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Nomor Telepon:</label>
                            <p class="text-gray-800">{{ $user->phone ?: 'Belum diisi' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Alamat:</label>
                            <p class="text-gray-800">{{ $user->address ?: 'Belum diisi' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Bergabung:</label>
                            <p class="text-gray-800">{{ $user->created_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-4">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 mr-4 bg-yellow-100 rounded-full">
                        <i class="text-xl text-yellow-600 fas fa-medal"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $badges->count() }}</p>
                        <p class="text-gray-600">Total Badges</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 mr-4 bg-green-100 rounded-full">
                        <i class="text-xl text-green-600 fas fa-hand-holding-heart"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $donations->count() }}</p>
                        <p class="text-gray-600">Total Donasi</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 mr-4 bg-purple-100 rounded-full">
                        <i class="text-xl text-purple-600 fas fa-file-alt"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $reports->count() }}</p>
                        <p class="text-gray-600">Total Laporan</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="p-3 mr-4 bg-blue-100 rounded-full">
                        <i class="text-xl text-blue-600 fas fa-hands-helping"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $volunteers->count() }}</p>
                        <p class="text-gray-600">Total Volunteer</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Badges Section -->
        <div class="mb-6 bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <i class="mr-3 text-xl text-yellow-600 fas fa-medal"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Badges User</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table id="badges-table" class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    ID</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Badge</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Diperoleh</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($badges as $badge)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $badge->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="mr-2 text-yellow-500 fas fa-medal"></i>
                                            {{ $badge->badge->title ?? 'Badge ID: ' . $badge->badge_id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        {{ $badge->created_at }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-sm text-center text-gray-500">
                                        Pengguna ini belum memiliki badge apapun.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="mb-6 bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <i class="mr-3 text-xl text-purple-600 fas fa-file-alt"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Riwayat Laporan Pengguna</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table id="reports-table" class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    ID</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Judul Laporan</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Kategori</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Status</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Tanggal Dibuat</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($reports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                        #{{ $report->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $report->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $report->city->name ?? '' }},
                                            {{ $report->district->name ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 rounded-lg">
                                            {{ ucfirst($report->category ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'verified' => 'bg-blue-100 text-blue-800',
                                                'on-progress' => 'bg-purple-100 text-purple-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$report->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('-', ' ', $report->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $report->created_at }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <a href="{{ route('report.show', $report->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">
                                        Pengguna ini belum membuat laporan apapun.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Volunteers Section -->
        <div class="mb-6 bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <i class="mr-3 text-xl text-blue-600 fas fa-hands-helping"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Riwayat Volunteer</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table id="volunteers-table" class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    ID</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Mission</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Status</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Role</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Sertifikat</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Tanggal</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($volunteers as $volunteer)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $volunteer->id }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        {{ $volunteer->mission->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if ($volunteer->participation_status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($volunteer->participation_status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($volunteer->participation_status === 'attended') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($volunteer->participation_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        @if ($volunteer->is_leader)
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">
                                                <i class="mr-1 fas fa-crown"></i>Leader
                                            </span>
                                        @else
                                            <span class="text-gray-500">Member</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        @if ($volunteer->certificate_url)
                                            <a href="{{ asset('storage/' . $volunteer->certificate_url) }}"
                                                target="_blank" class="text-blue-600 hover:text-blue-800">
                                                <i class="mr-1 fas fa-certificate"></i>Lihat Sertifikat
                                            </a>
                                        @else
                                            <span class="text-gray-500">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        {{ $volunteer->created_at }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <a href="{{ route('admin.missions.show', $volunteer->mission->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500">
                                        Pengguna ini belum mengikuti volunteer apapun.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Donations Section -->
        <div class="mb-6 bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <i class="mr-3 text-xl text-green-600 fas fa-hand-holding-heart"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Riwayat Donasi</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table id="donations-table" class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    ID</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Jumlah</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Report</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Status</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($donations as $donation)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $donation->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        <span class="font-medium text-green-600">Rp
                                            {{ number_format($donation->amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        Report ID: {{ $donation->report_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if ($donation->status === 'paid') bg-green-100 text-green-800
                                    @elseif($donation->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($donation->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        {{ $donation->created_at }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">
                                        Pengguna ini belum melakukan donasi apapun.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.tailwind.min.css">

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.tailwind.min.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        $(document).ready(function() {
            // Initialize all DataTables
            const dataTableConfig = {
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                },
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Semua"]
                ]
            };

            $('#badges-table').DataTable(dataTableConfig);
            $('#reports-table').DataTable(dataTableConfig);
            $('#volunteers-table').DataTable(dataTableConfig);
            $('#donations-table').DataTable(dataTableConfig);
        });
    </script>

    <style>
        /* Custom DataTables styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: #374151;
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem;
            margin: 0 0.125rem;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            margin: 0 0.5rem;
        }

        /* Add spacing between datatables */
        .dataTables_wrapper {
            margin-bottom: 2rem;
        }
    </style>
@endpush
