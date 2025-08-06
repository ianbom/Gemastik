<div id="changeStatusModal" tabindex="-1" aria-hidden="true"
         class="hidden fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Ubah Status Laporan #<span id="reportId">123</span>
                    </h3>
                    <button type="button" data-modal-hide="changeStatusModal"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>

                {{-- Form akan diisi action-nya oleh JavaScript --}}
                <form id="statusForm" method="POST" class="p-4 md:p-5">
                    @csrf
                    @method('PUT')

                    {{-- <div class="grid gap-4 mb-4 grid-cols-1">
                        <div>
                            <label for="assigned_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tugaskan ke</label>
                            <select id="assigned_type" name="assigned_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="community" selected>Komunitas</option>
                                <option value="volunteer">Relawan</option>
                            </select>
                        </div>
                        <div>
                            <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan (Opsional)</label>
                            <textarea id="notes" name="notes" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tambahkan catatan jika perlu..."></textarea>
                        </div>
                    </div> --}}

                    <div class="flex items-center space-x-4 border-t pt-4 mt-4">
                        <button type="submit" id="acceptBtn"
                                class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            Terima & Verifikasi
                        </button>
                        <button type="submit" id="rejectBtn"
                                class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            Tolak Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
</div>

@push('scripts')

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('changeStatusModal');
            const form = document.getElementById('statusForm');
            const reportIdSpan = document.getElementById('reportId');
            const acceptBtn = document.getElementById('acceptBtn');
            const rejectBtn = document.getElementById('rejectBtn');

            // Event listener untuk membuka modal
            document.querySelectorAll('.open-modal-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const reportId = this.dataset.reportId;
                    const acceptUrl = this.dataset.acceptUrl;
                    const rejectUrl = this.dataset.rejectUrl;

                    // Update report ID di modal
                    reportIdSpan.textContent = reportId;

                    // Simpan URL untuk nanti digunakan
                    form.dataset.acceptUrl = acceptUrl;
                    form.dataset.rejectUrl = rejectUrl;

                    // Tampilkan modal
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');

                    // Focus ke modal untuk accessibility
                    modal.focus();
                });
            });

            // Event listener untuk menutup modal
            document.querySelectorAll('[data-modal-hide="changeStatusModal"]').forEach(button => {
                button.addEventListener('click', function() {
                    closeModal();
                });
            });

            // Tutup modal ketika klik di luar modal
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Tutup modal dengan ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            // Handle form submission
            acceptBtn.addEventListener('click', function(e) {
                e.preventDefault();
                form.action = form.dataset.acceptUrl;
                form.submit();
            });

            rejectBtn.addEventListener('click', function(e) {
                e.preventDefault();
                form.action = form.dataset.rejectUrl;
                form.submit();
            });

            function closeModal() {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');

                // Reset form
                form.reset();
                form.removeAttribute('action');
            }
        });
</script>


@endpush
