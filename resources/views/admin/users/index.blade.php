@extends('admin.layouts.app')

@section('content')

    @include('admin.components.notification')

    <div class="min-h-screen rounded-lg shadow-lg bg-gray-50">
        <div class="container px-4 py-8 mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="mb-2 text-3xl font-bold text-gray-900">Manajemen User</h1>
                        <p class="text-lg text-gray-600">Kelola dan pantau semua user yang terdaftar</p>
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
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total User</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $users->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                       <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
</svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Admin</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $users->where('role', 'admin')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Komunitas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $users->where('role', 'community')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Warga</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $users->where('role', 'citizen')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="mb-8 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">Filter User</h2>
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
                    <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-6">
                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Role Filter -->
                            <div class="space-y-2">
                                <label for="role" class="block text-sm font-semibold text-gray-700">Role</label>
                                <select name="role" id="role"
                                    class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager
                                    </option>
                                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>

                            <!-- Province Filter -->
                            <div class="space-y-2">
                                <label for="province_id" class="block text-sm font-semibold text-gray-700">Provinsi</label>
                                <select name="province_id" id="province_id"
                                    class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Semua Provinsi</option>
                                    @if (isset($provinces))
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}"
                                                {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    @endif
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
                        </div>

                        <!-- Row 2 -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
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

                            <!-- Search Filter -->
                            <div class="space-y-2">
                                <label for="search" class="block text-sm font-semibold text-gray-700">Cari Nama</label>
                                <div class="relative">
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                        placeholder="Cari berdasarkan nama user..."
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

                            <!-- Created Date Range -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Tanggal Mendaftar</label>
                                <div class="flex space-x-3">
                                    <input type="date" name="created_from" value="{{ request('created_from') }}"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <input type="date" name="created_to" value="{{ request('created_to') }}"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col justify-end pt-6 space-y-3 border-t border-gray-100 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('admin.users.index') }}"
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

                            <a href="{{ route('admin.users.create') }}"
                                class="inline-flex items-center px-6 py-3 font-medium text-white transition-all duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah User
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="overflow-x-auto">
                    <table id="usersTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    ID
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Nama
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Email
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Role
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Provinsi/Kota
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Kecamatan
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Tanggal Mendaftar
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr class="transition-colors duration-150 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-gray-100 rounded-lg">#{{ $user->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <img class="object-cover w-10 h-10 rounded-full"
                                                    src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                                    alt="{{ $user->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->phone ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-red-100 text-red-800 border-red-200',
                                                'manager' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                'user' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full border {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <div class="font-semibold text-gray-900">{{ $user->province->name ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $user->city->name ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $user->district->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                class="text-blue-600 transition-colors duration-150 hover:text-blue-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                            {{-- <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="text-green-600 transition-colors duration-150 hover:text-green-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a> --}}
                                            {{-- <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 transition-colors duration-150 hover:text-red-800"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
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
            $('#usersTable').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",

                }
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
                    {{ request()->hasAny(['role', 'province_id', 'city_id', 'district_id', 'search', 'created_from', 'created_to']) ? 'true' : 'false' }};

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
            $('#usersTable tbody tr').hover(
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
