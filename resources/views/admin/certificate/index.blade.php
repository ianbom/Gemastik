@extends('admin.layouts.app')

@section('content')
    @include('admin.components.notification')

    <div class="min-h-screen rounded-lg shadow-lg bg-gray-50">
        <div class="container px-4 py-8 mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="mb-2 text-3xl font-bold text-gray-900">Manajemen Sertifikat</h1>
                        <p class="text-lg text-gray-600">Kelola semua template sertifikat yang tersedia.</p>
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
                <div class="col-span-1 p-6 bg-white border border-gray-100 shadow-sm rounded-xl md:col-span-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Sertifikat</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $certificates->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="mb-8 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">Filter Sertifikat</h2>
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
                    <form method="GET" action="{{ route('admin.certificates.index') }}" class="space-y-6">
                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Search Filter -->
                            <div class="space-y-2">
                                <label for="search" class="block text-sm font-semibold text-gray-700">Cari
                                    Sertifikat</label>
                                <div class="relative">
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                        placeholder="Cari berdasarkan nama..."
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
                                <label class="block text-sm font-semibold text-gray-700">Tanggal Dibuat</label>
                                <div class="flex space-x-3">
                                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col justify-end pt-6 space-y-3 border-t border-gray-100 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('admin.certificates.index') }}"
                                class="px-6 py-3 font-medium text-center text-gray-700 transition-all duration-200 bg-gray-100 rounded-lg hover:bg-gray-200">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset Filter
                            </a>
                            <button type="submit"
                                class="px-6 py-3 font-medium text-white transition-all duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                                Terapkan Filter
                            </button>

                            {{-- <a href="{{ route('admin.certificates.create') }}"
                                class="inline-flex items-center px-6 py-3 font-medium text-white transition-all duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Buat Sertifikat
                            </a> --}}
                        </div>
                    </form>
                </div>
            </div>

            <!-- Certificate Table -->
            <div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="overflow-x-auto">
                    <table id="certificateTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    ID
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Nama Sertifikat
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Template
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Tanggal Dibuat
                                </th>
                                {{-- <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Aksi
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($certificates as $certificate)
                                <tr class="transition-colors duration-150 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-gray-100 rounded-lg">#{{ $certificate->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="max-w-xs">
                                            <p class="font-medium truncate" title="{{ $certificate->name }}">
                                                {{ $certificate->name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="max-w-xs">
                                            <p class="truncate" title="{{ $certificate->template }}">
                                                {{ $certificate->template }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $certificate->created_at ? $certificate->created_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            {{-- <a href="{{ route('admin.certificates.show', $certificate->id) }}"
                                                class="text-blue-600 transition-colors duration-150 hover:text-blue-800"
                                                title="Lihat">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a> --}}
                                            {{-- <a href="{{ route('admin.certificates.edit', $certificate->id) }}"
                                                class="text-green-600 transition-colors duration-150 hover:text-green-800"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a> --}}
                                            {{-- <form action="{{ route('admin.certificates.destroy', $certificate->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 transition-colors duration-150 hover:text-red-800"
                                                    title="Hapus"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus sertifikat ini?')">
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
                {{-- <div class="p-6 border-t border-gray-200">
                {{ $certificates->links() }}
            </div> --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#certificateTable').DataTable({
                order: [
                    [0, 'desc']
                ], // Urutkan berdasarkan kolom ID (kolom ke-0) secara descending
                columnDefs: [{
                        orderable: false,
                        targets: 4
                    } // Nonaktifkan pengurutan pada kolom Aksi (kolom ke-4)
                ],
                // Mengatur bahasa UI DataTables ke Bahasa Indonesia
                language: {
                    "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "sProcessing": "Sedang memproses...",
                    "sLengthMenu": "Tampilkan _MENU_ data",
                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "sInfoFiltered": "(disaring dari _MAX_ total data)",
                    "sSearch": "Cari:",
                }
            });

            // Logika untuk toggle filter (menggunakan jQuery untuk animasi)
            $('#toggleFilters').click(function() {
                const filterForm = $('#filterForm');
                const toggleText = $(this).find('span');

                filterForm.slideToggle(300, function() {
                    if (filterForm.is(':visible')) {
                        toggleText.text('Sembunyikan Filter');
                    } else {
                        toggleText.text('Tampilkan Filter');
                    }
                });
            });

            // Sembunyikan filter secara otomatis di mobile jika tidak ada filter aktif
            if (window.innerWidth < 768) {
                const hasActiveFilters =
                    {{ request()->hasAny(['search', 'date_from', 'date_to']) ? 'true' : 'false' }};
                if (!hasActiveFilters) {
                    $('#filterForm').hide();
                    $('#toggleFilters span').text('Tampilkan Filter');
                }
            }

            // Tampilkan filter jika ada filter yang aktif saat halaman dimuat
            const hasActiveFilters =
            {{ request()->hasAny(['search', 'date_from', 'date_to']) ? 'true' : 'false' }};
            if (hasActiveFilters) {
                $('#filterForm').show();
                $('#toggleFilters span').text('Sembunyikan Filter');
            }
        });
    </script>
@endpush
