 <table id="reportsTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Judul
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Pelapor
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kota/Kecamatan
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal Dibuat
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reports as $report)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    <span class="bg-gray-100 px-2 py-1 rounded-lg">#{{ $report->id }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="max-w-xs">
                                        <p class="truncate font-medium">{{ $report->title ?? '-' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $report->address ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-blue-600 font-semibold text-xs">
                                                {{ substr($report->reporter->name ?? 'U', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $report->reporter->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div class="space-y-1">
                                        <div class="font-semibold text-gray-900">{{ $report->city->name ?? '-' }}</div>
                                        <div class="text-gray-500 text-xs">{{ $report->district->name ?? '-' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span class="bg-gray-100 px-2 py-1 rounded-lg text-xs font-medium">
                                        {{ ucfirst($report->category ?? '-') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'verified' => 'bg-green-100 text-green-800 border-green-200',
                                            'rejected' => 'bg-red-100 text-red-800 border-red-200'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Menunggu',
                                            'in_progress' => 'Diproses',
                                            'verified' => 'Terverifikasi',
                                            'rejected' => 'Ditolak'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full border {{ $statusColors[$report->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        {{ $statusLabels[$report->status] ?? ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $report->created_at ? $report->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">

                                        {{-- PERBAIKAN: Tambahkan class 'open-modal-btn' dan data-* atribut --}}

                                        <a href="{{ route('admin.reports.edit', $report->id) }}"
                                           title="Lihat Detail"
                                           class="text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                            Lihat Detail
                                        </a>
                                        {{-- <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-150"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
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

 @push('scripts')


<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#reportsTable').DataTable({
            "lengthMenu": [
                [10, 50, 100, -1],      // Nilai yang dikirim
                [10, 50, 100, "Semua"] // Teks yang ditampilkan
            ],
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
            const hasActiveFilters = {{ request()->hasAny(['status', 'city_id', 'district_id', 'assigned_to_type', 'search', 'date_from', 'date_to', 'created_from', 'created_to', 'completed_from', 'completed_to']) ? 'true' : 'false' }};

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
            submitBtn.html('<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...');
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

