@extends('admin.layouts.app')

@section('content')
    @include('admin.components.notification')

    <div class="min-h-screen rounded-lg shadow-lg bg-gray-50">
        <div class="container px-4 py-8 mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="mb-2 text-3xl font-bold text-gray-900">Manajemen Quiz</h1>
                        <p class="text-lg text-gray-600">Kelola dan pantau semua quiz yang tersedia</p>
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
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Quiz</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $quizzes->count() }}</p>
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
                            <p class="text-sm font-medium text-gray-600">Quiz Aktif</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $quizzes->where('is_active', true)->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Poin</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $quizzes->sum('points_reward') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Quiz Sulit</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $quizzes->where('difficulty', 'sulit')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="mb-8 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">Filter Quiz</h2>
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

                <div id="filterForm" class="p-6" style="display: none;">
                    <form method="GET" action="{{ route('admin.quizzes.index') }}" class="space-y-6">
                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Difficulty Filter -->
                            <div class="space-y-2">
                                <label for="difficulty" class="block text-sm font-semibold text-gray-700">Tingkat
                                    Kesulitan</label>
                                <select name="difficulty" id="difficulty"
                                    class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Semua Tingkat</option>
                                    <option value="mudah" {{ request('difficulty') == 'mudah' ? 'selected' : '' }}>Mudah
                                    </option>
                                    <option value="sedang" {{ request('difficulty') == 'sedang' ? 'selected' : '' }}>Sedang
                                    </option>
                                    <option value="sulit" {{ request('difficulty') == 'sulit' ? 'selected' : '' }}>Sulit
                                    </option>
                                </select>
                            </div>

                            <!-- Search Filter -->
                            <div class="space-y-2">
                                <label for="search" class="block text-sm font-semibold text-gray-700">Cari Quiz</label>
                                <div class="relative">
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                        placeholder="Cari berdasarkan judul..."
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

                            <!-- Status Filter -->
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-semibold text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        Non-Aktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col justify-end pt-6 space-y-3 border-t border-gray-100 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('admin.quizzes.index') }}"
                                class="px-6 py-3 font-medium text-center text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Reset
                                Filter</a>
                            <button type="submit"
                                class="px-6 py-3 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Terapkan
                                Filter</button>
                            <a href="{{ route('admin.quizzes.create') }}"
                                class="inline-flex items-center px-6 py-3 font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Buat Quiz
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quiz Table -->
            <div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="p-6 overflow-x-auto">
                    <table id="quizTable" class="min-w-full" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    ID</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Thumbnail</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Judul</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Kesulitan</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Poin</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Tanggal Dibuat</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($quizzes as $quiz)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold whitespace-nowrap">#{{ $quiz->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($quiz->thumbnail_url)
                                            <img src="{{ Storage::url($quiz->thumbnail_url) }}" alt="Thumbnail"
                                                class="object-cover w-12 h-12 rounded-lg">
                                        @else
                                            <div class="flex items-center justify-center w-12 h-12 bg-gray-200 rounded-lg">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg></div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <p class="font-medium truncate" title="{{ $quiz->title }}">{{ $quiz->title }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $difficultyColors = [
                                                'mudah' => 'bg-green-100 text-green-800',
                                                'sedang' => 'bg-yellow-100 text-yellow-800',
                                                'sulit' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full {{ $difficultyColors[$quiz->difficulty] ?? 'bg-gray-100' }}">{{ ucfirst($quiz->difficulty) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold whitespace-nowrap">
                                        {{ $quiz->points_reward }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($quiz->is_active)
                                            <span
                                                class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Aktif</span>
                                        @else
                                            <span
                                                class="px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap">
                                        {{ $quiz->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            {{-- <a href="{{ route('admin.quizzes.show', $quiz->id) }}"
                                                class="text-blue-600 hover:text-blue-800" title="Lihat Detail"><svg
                                                    class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg></a> --}}
                                            <a href="{{ route('admin.quizzes.edit', $quiz->id) }}"
                                                class="text-green-600 hover:text-green-800" title="Edit Quiz"><svg
                                                    class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg></a>
                                            {{-- <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST"
                                                class="inline">@csrf @method('DELETE') <button type="submit"
                                                    class="text-red-600 hover:text-red-800" title="Hapus Quiz"
                                                    onclick="return confirm('Hapus quiz ini?')"><svg class="w-5 h-5"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg></button></form> --}}
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
            $('#quizTable').DataTable({
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                        orderable: false,
                        targets: [1, -1]
                    } // Disable ordering on thumbnail and actions columns
                ],
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

            // Toggle filter functionality
            $('#toggleFilters').click(function() {
                const filterForm = $('#filterForm');
                const toggleText = $(this).find('span');

                filterForm.slideToggle(300, function() {
                    if ($(this).is(':visible')) {
                        toggleText.text('Sembunyikan Filter');
                    } else {
                        toggleText.text('Tampilkan Filter');
                    }
                });
            });

            // Sembunyikan filter secara default di mobile jika tidak ada filter aktif
            if (window.innerWidth < 768) {
                const hasActiveFilters =
                    {{ request()->hasAny(['difficulty', 'search', 'status', 'date_from', 'date_to', 'points_min', 'points_max']) ? 'true' : 'false' }};
                if (!hasActiveFilters) {
                    $('#filterForm').hide();
                    $('#toggleFilters').find('span').text('Tampilkan Filter');
                } else {
                    $('#filterForm').show();
                    $('#toggleFilters').find('span').text('Sembunyikan Filter');
                }
            } else {
                $('#filterForm').show();
            }
        });
    </script>
@endpush
