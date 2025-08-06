@extends('admin.layouts.app')

@section('content')

    @include('admin.components.notification')

    <div class="min-h-screen rounded-lg shadow-lg bg-gray-50">
        <div class="container px-4 py-8 mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="mb-2 text-3xl font-bold text-gray-900">Manajemen Misi</h1>
                        <p class="text-lg text-gray-600">Kelola dan pantau semua misi yang tersedia</p>
                    </div>
                    <div class="items-center hidden space-x-4 md:flex">
                        <div class="px-4 py-2 bg-white rounded-lg shadow-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">System Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Misi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $missions->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $missions->where('status', 'pending')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">In Progress</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $missions->where('status', 'in_progress')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Completed</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $missions->where('status', 'completed')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="mb-8 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">Filter Misi</h2>
                        <button type="button" id="toggleFilters" class="font-medium text-blue-600 hover:text-blue-800">
                            <span class="hidden md:inline">Sembunyikan Filter</span>
                            <svg class="w-5 h-5 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="filterForm" class="p-6">
                    <form method="GET" action="{{ route('admin.missions.index') }}" class="space-y-6">
                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Status Filter -->
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-semibold text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Semua Status</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Terbuka
                                    </option>
                                    <option value="on-progress" {{ request('status') == 'on-progress' ? 'selected' : '' }}>
                                        Dalam Proses</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Selesai</option>
                                    <option value="under-authority"
                                        {{ request('status') == 'under-authority' ? 'selected' : '' }}>Pihak Berwenang
                                    </option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Dibatalkan</option>
                                </select>
                            </div>


                            <!-- City Filter -->
                            <div class="space-y-2">
                                <label for="city_id" class="block text-sm font-semibold text-gray-700">Kota</label>
                                <select name="city_id" id="city_id"
                                    class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Semua Kota</option>
                                    @if (isset($cities))
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- District Filter -->
                            <div class="space-y-2">
                                <label for="district_id"
                                    class="block text-sm font-semibold text-gray-700">Kecamatan</label>
                                <select name="district_id" id="district_id"
                                    class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Semua Kecamatan</option>
                                    @if (isset($districts))
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}"
                                                {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Assigned To Type Filter -->
                            {{-- <div class="space-y-2">
                            <label for="assigned_to_type" class="block text-sm font-semibold text-gray-700">Tipe Penugasan</label>
                            <select name="assigned_to_type" id="assigned_to_type" class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Tipe</option>
                                <option value="user" {{ request('assigned_to_type') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="team" {{ request('assigned_to_type') == 'team' ? 'selected' : '' }}>Team</option>
                                <option value="admin" {{ request('assigned_to_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div> --}}

                            <!-- Search Filter -->
                            <div class="space-y-2">
                                <label for="search" class="block text-sm font-semibold text-gray-700">Cari
                                    Alamat</label>
                                <div class="relative">
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                        placeholder="Cari berdasarkan alamat..."
                                        class="w-full px-4 py-3 pl-10 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Date Range Filter -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Tanggal Terjadwal</label>
                                <div class="flex space-x-3">
                                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Row 3 - Additional Date Filters -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Created Date Range -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Tanggal Dibuat</label>
                                <div class="flex space-x-3">
                                    <input type="date" name="created_from" value="{{ request('created_from') }}"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <input type="date" name="created_to" value="{{ request('created_to') }}"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <!-- Completed Date Range -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Tanggal Selesai</label>
                                <div class="flex space-x-3">
                                    <input type="date" name="completed_from" value="{{ request('completed_from') }}"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <input type="date" name="completed_to" value="{{ request('completed_to') }}"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col justify-end pt-6 space-y-3 border-t border-gray-100 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('admin.missions.index') }}"
                                class="px-6 py-3 font-medium text-center text-gray-700 transition-all duration-200 bg-gray-100 rounded-lg hover:bg-gray-200">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset Filter
                            </a>
                            <button type="submit"
                                class="px-6 py-3 font-medium text-white transition-all duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                                Terapkan Filter
                            </button>

                            <a href="{{ route('admin.missions.create') }}"
                                class="inline-flex items-center px-6 py-3 font-medium text-white transition-all duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16.5 3a2.121 2.121 0 113 3L7 18.5 3 21l2.5-4L16.5 3z" />
                                </svg>
                                Buat Misi
                            </a>


                        </div>
                    </form>
                </div>
            </div>


            <!-- Missions Table -->

            <div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="overflow-x-auto">
                    <table id="missionsTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    ID
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Judul
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Kota/Kecamatan
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Status
                                </th>
                                {{-- <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                Tipe Penugasan
                            </th> --}}
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Tanggal Terjadwal
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Tanggal Dibuat
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($missions as $index => $mission)
                                <tr class="transition-colors duration-150 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-gray-100 rounded-lg">#{{ $mission->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="max-w-xs">
                                            <p class="font-medium truncate">{{ $mission->title ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <div class="font-semibold text-gray-900">{{ $mission->city->name ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $mission->district->name ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'completed' => 'bg-green-100 text-green-800 border-green-200',
                                                'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Pending',
                                                'in_progress' => 'In Progress',
                                                'completed' => 'Completed',
                                                'cancelled' => 'Cancelled',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full border {{ $statusColors[$mission->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                            {{ $statusLabels[$mission->status] ?? ucfirst($mission->status) }}
                                        </span>
                                    </td>
                                    {{-- <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 rounded-lg">
                                        {{ ucfirst($mission->assigned_to_type ?? '-') }}
                                    </span>
                                </td> --}}
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $mission->scheduled_date ? \Carbon\Carbon::parse($mission->scheduled_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $mission->created_at ? $mission->created_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            {{-- <a href="{{ route('report.show', $mission->report_id) }}"
                                           class="text-blue-600 transition-colors duration-150 hover:text-blue-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a> --}}
                                            <a href="{{ route('admin.missions.edit', $mission->id) }}"
                                                class="text-green-600 transition-colors duration-150 hover:text-green-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            {{-- <form action="{{ route('admin.missions.destroy', $mission->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 transition-colors duration-150 hover:text-red-800"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus misi ini?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#missionsTable').DataTable({
                order: [
                    [5, 'desc']
                ],
                lengthMenu: [
                    [10, 50, 100, -1],
                    [10, 50, 100, "All"]
                ]
            });

            // Toggle filter functionality
            $('#toggleFilters').click(function() {
                const filterForm = $('#filterForm');
                const toggleText = $(this).find('span');

                if (filterForm.is(':visible')) {
                    filterForm.slideUp(300);
                    toggleText.text('Tampilkan Filter');
                } else {
                    filterForm.slideDown(300);
                    toggleText.text('Sembunyikan Filter');
                }
            });

            // Auto-hide filters on mobile if no active filters
            if (window.innerWidth < 768) {
                const hasActiveFilters =
                    {{ request()->hasAny(['status', 'city_id', 'district_id', 'assigned_to_type', 'search', 'date_from', 'date_to', 'created_from', 'created_to', 'completed_from', 'completed_to']) ? 'true' : 'false' }};

                if (!hasActiveFilters) {
                    $('#filterForm').hide();
                    $('#toggleFilters span').text('Tampilkan Filter');
                }
            }

            // Enhanced form interactions
            $('select, input').focus(function() {
                $(this).addClass('ring-2 ring-blue-500');
            }).blur(function() {
                $(this).removeClass('ring-2 ring-blue-500');
            });

            // Add loading state to filter button
            $('form').submit(function() {
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.html(
                    '<svg class="inline w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...'
                    );
                submitBtn.prop('disabled', true);

                // Re-enable button after 3 seconds as fallback
                setTimeout(function() {
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }, 3000);
            });

            // Smooth hover animations for table rows
            $('#missionsTable tbody tr').hover(
                function() {
                    $(this).addClass('transform scale-[1.01] transition-transform duration-150');
                },
                function() {
                    $(this).removeClass('transform scale-[1.01] transition-transform duration-150');
                }
            );


        });
    </script>
@endpush
